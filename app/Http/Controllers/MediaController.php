<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Allowed MIME types for upload
     */
    protected $allowedMimes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        'application/pdf',
        'video/mp4', 'video/webm',
        'audio/mpeg', 'audio/wav',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip', 'application/x-zip-compressed',
    ];

    /**
     * Display the media library.
     */
    public function index(Request $request)
    {
        $type = $request->input('type', 'all');
        $search = $request->input('search', '');
        $view = $request->input('view', 'grid');

        $files = $this->getMediaFiles($type, $search);

        return view('dashboard.media.index', compact('files', 'type', 'search', 'view'));
    }

    /**
     * Upload one or multiple files.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:20480', // 20MB max
        ]);

        $uploaded = [];
        $errors = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if (!in_array($file->getMimeType(), $this->allowedMimes)) {
                    $errors[] = $file->getClientOriginalName() . ': File type not allowed.';
                    continue;
                }

                // Build a unique filename
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $safeName = Str::slug($originalName) . '-' . uniqid() . '.' . $extension;

                // Store under media/year/month/
                $folder = 'media/' . date('Y/m');
                $path = $file->storeAs($folder, $safeName, 'public');

                $uploaded[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url'  => asset('storage/' . $path),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ];
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'uploaded' => $uploaded,
                'errors' => $errors,
            ]);
        }

        $msg = count($uploaded) . ' file(s) uploaded successfully.';
        if (!empty($errors)) {
            $msg .= ' ' . count($errors) . ' file(s) failed.';
        }

        return redirect()->route('superuser.media.index')->with('status', $msg);
    }

    /**
     * Delete a media file.
     */
    public function destroy(Request $request)
    {
        $path = $request->input('path');

        if (!$path || !Str::startsWith($path, 'media/')) {
            return response()->json(['success' => false, 'message' => 'Invalid path.'], 400);
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File not found.'], 404);
    }

    /**
     * Get all media files from storage with optional filtering.
     */
    protected function getMediaFiles(string $type = 'all', string $search = ''): array
    {
        $allFiles = Storage::disk('public')->allFiles('media');
        $files = [];

        foreach ($allFiles as $filePath) {
            $fileName = basename($filePath);

            // Filter by search
            if ($search && !Str::contains(strtolower($fileName), strtolower($search))) {
                continue;
            }

            $mime = $this->getMimeFromExtension($fileName);

            // Filter by type
            if ($type !== 'all') {
                if ($type === 'image' && !Str::startsWith($mime, 'image/')) continue;
                if ($type === 'video' && !Str::startsWith($mime, 'video/')) continue;
                if ($type === 'audio' && !Str::startsWith($mime, 'audio/')) continue;
                if ($type === 'document' && !in_array(true, [
                    Str::startsWith($mime, 'application/'),
                    Str::startsWith($mime, 'text/'),
                ])) continue;
            }

            $fullPath = Storage::disk('public')->path($filePath);
            $size = file_exists($fullPath) ? filesize($fullPath) : 0;
            $lastModified = file_exists($fullPath) ? filemtime($fullPath) : 0;

            $files[] = [
                'path'          => $filePath,
                'name'          => $fileName,
                'url'           => asset('storage/' . $filePath),
                'mime'          => $mime,
                'size'          => $size,
                'size_human'    => $this->formatBytes($size),
                'last_modified' => $lastModified,
                'date'          => date('d M Y', $lastModified),
                'is_image'      => Str::startsWith($mime, 'image/') && $mime !== 'image/svg+xml',
                'is_video'      => Str::startsWith($mime, 'video/'),
                'is_audio'      => Str::startsWith($mime, 'audio/'),
                'is_pdf'        => $mime === 'application/pdf',
                'type_label'    => $this->getTypeLabel($mime),
            ];
        }

        // Sort by newest first
        usort($files, fn($a, $b) => $b['last_modified'] - $a['last_modified']);

        return $files;
    }

    protected function getMimeFromExtension(string $filename): string
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return match($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'gif'         => 'image/gif',
            'webp'        => 'image/webp',
            'svg'         => 'image/svg+xml',
            'pdf'         => 'application/pdf',
            'mp4'         => 'video/mp4',
            'webm'        => 'video/webm',
            'mp3'         => 'audio/mpeg',
            'wav'         => 'audio/wav',
            'doc'         => 'application/msword',
            'docx'        => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'         => 'application/vnd.ms-excel',
            'xlsx'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'zip'         => 'application/zip',
            default       => 'application/octet-stream',
        };
    }

    protected function getTypeLabel(string $mime): string
    {
        if (Str::startsWith($mime, 'image/')) return 'Image';
        if (Str::startsWith($mime, 'video/')) return 'Video';
        if (Str::startsWith($mime, 'audio/')) return 'Audio';
        if ($mime === 'application/pdf') return 'PDF';
        return 'Document';
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
