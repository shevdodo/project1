<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'title', 'url', 'target', 'type', 'reference_id', 'parent_id', 'order'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function getResolvedUrlAttribute()
    {
        if ($this->type === 'custom') {
            return $this->url;
        }

        if ($this->type === 'page') {
            $page = Page::find($this->reference_id);
            return $page ? url('/' . $page->slug) : '#';
        }

        if ($this->type === 'category') {
            // Adjust this if you have a specific route for category frontend
            $category = Category::find($this->reference_id);
            return $category ? url('/category/' . $category->slug) : '#';
        }

        return '#';
    }
}
