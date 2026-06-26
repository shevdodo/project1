<x-layouts.dashboard title="Edit User">
    <div class="mb-8">
        <a href="{{ route('superuser.users.index') }}" class="text-sm text-brand-600 hover:text-brand-800 flex items-center space-x-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Users</span>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit User: {{ $user->name }}</h2>
        <p class="text-sm text-gray-500 mt-1">Update account details or change role.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm font-medium">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm max-w-2xl">
        <form method="POST" action="{{ route('superuser.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-800 mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
            </div>

            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-4">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Change Password (Leave blank to keep current)</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-800 mb-1">New Password</label>
                        <input type="password" id="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-800 mb-1">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                    </div>
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-semibold text-gray-800 mb-1">Role</label>
                <select id="role" name="role" required class="w-full sm:max-w-xs border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white" {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    <option value="superuser" {{ old('role', $user->role) == 'superuser' ? 'selected' : '' }}>Superuser</option>
                </select>
                @if($user->id === Auth::id())
                    <p class="text-xs text-red-500 mt-1">You cannot change your own role.</p>
                    <input type="hidden" name="role" value="{{ $user->role }}">
                @endif
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/30">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
