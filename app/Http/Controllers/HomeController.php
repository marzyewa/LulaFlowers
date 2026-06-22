<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('welcome', [
            'latestPosts' => Post::with(['category', 'user'])
                ->latest()
                ->take(6)
                ->get(),
            'categories' => Category::withCount('posts')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
