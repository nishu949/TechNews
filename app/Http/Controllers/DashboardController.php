<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If user is author, show author dashboard
        if ($user->isAuthor()) {
            $articles = Article::where('user_id', $user->id)
                ->with(['category', 'tags'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            $stats = [
                'total_articles' => Article::where('user_id', $user->id)->count(),
                'published_articles' => Article::where('user_id', $user->id)
                    ->where('status', 'published')->count(),
                'total_comments' => Comment::whereHas('article', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->count(),
                'recent_comments' => Comment::whereHas('article', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->with(['user', 'article'])->latest()->limit(5)->get(),
            ];
            
            return view('dashboard.author', compact('user', 'articles', 'stats'));
        }
        
        // Reader dashboard
        $comments = Comment::where('user_id', $user->id)
            ->with('article')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('dashboard.reader', compact('user', 'comments'));
    }
}