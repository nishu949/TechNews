<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;

class ArticleController extends Controller
{
    /**
     * Display homepage with all published articles
     */
    public function index()
    {
        $articles = Article::with([
            'author',
            'category',
            'tags'
        ])
        ->where('status', 'published')
        ->latest('published_at')
        ->paginate(6);

        return view('home.index', compact('articles'));
    }

    /**
     * Search articles
     */
    public function search(Request $request)
    {
        $keyword = trim($request->search);

        if (!$keyword) {
            return redirect()->route('home');
        }

        $articles = Article::with(['author', 'category', 'tags'])
            ->where('status', 'published')
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('excerpt', 'LIKE', "%{$keyword}%")
                    ->orWhere('content', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('category', function ($category) use ($keyword) {
                        $category->where('name', 'LIKE', "%{$keyword}%");
                    })
                    ->orWhereHas('tags', function ($tag) use ($keyword) {
                        $tag->where('name', 'LIKE', "%{$keyword}%");
                    });
            })
            ->latest('published_at')
            ->paginate(6);

        return view('articles.search', compact('articles', 'keyword'));
    }

    /**
     * Show Create Article Form
     */
/**
 * Show Create Article Form
 */
public function create()
{
    // Check if user is logged in
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Please login to write articles.');
    }

    // FORCE REFRESH USER FROM DATABASE
    $user = auth()->user();
    $user->refresh(); // This reloads the user from database

    // Check if user is author
    if (!$user->isAuthor()) {
        // Log the issue for debugging
        \Log::error('User is not an author', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'is_author' => $user->isAuthor()
        ]);

        abort(403, 'Only authors can create articles. Your role is: ' . $user->role);
    }

    $categories = Category::all();
    $tags = Tag::all();

    return view('articles.create', compact('categories', 'tags'));
}

/**
 * Store New Article
 */
public function store(Request $request)
{
    // Check if user is logged in
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Please login to publish articles.');
    }

    // FORCE REFRESH USER FROM DATABASE
    $user = auth()->user();
    $user->refresh();

    // Check if user is author
    if (!$user->isAuthor()) {
        abort(403, 'Only authors can create articles. Your role is: ' . $user->role);
    }

    $validated = $request->validate([
        'title' => 'required|max:255',
        'category_id' => 'required|exists:categories,id',
        'excerpt' => 'nullable|max:500',
        'content' => 'required',
        'featured_image' => 'nullable|image|max:2048',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
    ]);

    $imagePath = null;

    if ($request->hasFile('featured_image')) {
        $imagePath = $request->file('featured_image')
            ->store('articles', 'public');
    }

    $article = Article::create([
        'user_id' => auth()->id(),
        'category_id' => $validated['category_id'],
        'title' => $validated['title'],
        'slug' => Str::slug($validated['title']) . '-' . time(),
        'excerpt' => $validated['excerpt'],
        'content' => $validated['content'],
        'featured_image' => $imagePath,
        'status' => 'published',
        'published_at' => now(),
    ]);

    if ($request->has('tags')) {
        $article->tags()->sync($request->tags);
    }

    return redirect()
        ->route('articles.show', $article->slug)
        ->with('success', 'Article published successfully!');
}

    /**
     * Display one article
     */
  /**
 * Display one article
 */
public function show(Article $article)
{
    // Check if article is published or user is author
    if ($article->status !== 'published' && auth()->id() !== $article->user_id) {
        abort(404);
    }

    // INCREMENT VIEWS
    $article->incrementViews();

    // Convert Markdown to HTML
    $converter = new \League\CommonMark\CommonMarkConverter([
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ]);
    
    $html = $converter->convert($article->content);

    // Load relationships
    $article->load([
        'author',
        'category',
        'tags',
        'comments.user'
    ]);

    // Get related articles
    $relatedArticles = Article::published()
        ->where('id', '!=', $article->id)
        ->where(function($query) use ($article) {
            $query->where('category_id', $article->category_id)
                ->orWhereHas('tags', function($q) use ($article) {
                    $q->whereIn('tags.id', $article->tags->pluck('id'));
                });
        })
        ->with(['author', 'category'])
        ->limit(4)
        ->get();

    return view('articles.show', compact('article', 'html', 'relatedArticles'));
}
    /**
     * Edit Article Form
     */
    public function edit(Article $article)
    {
        if (!auth()->user()->isAuthor() || auth()->id() !== $article->user_id) {
            abort(403, 'You can only edit your own articles.');
        }

        $categories = Category::all();
        $tags = Tag::all();

        return view('articles.edit', compact(
            'article',
            'categories',
            'tags'
        ));
    }

    /**
     * Update Article
     */
  /**
 * Update Article
 */
public function update(Request $request, Article $article)
{
    if (!auth()->user()->isAuthor() || auth()->id() !== $article->user_id) {
        abort(403, 'You can only edit your own articles.');
    }

    $validated = $request->validate([
        'title' => 'required|max:255',
        'category_id' => 'required|exists:categories,id',
        'excerpt' => 'nullable|max:500',
        'content' => 'required',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
        'featured_image' => 'nullable|image|max:2048',
    ]);

    // Handle new image upload
    if ($request->hasFile('featured_image')) {
        // Delete old image
        if ($article->featured_image) {
            \Storage::disk('public')->delete($article->featured_image);
        }
        $imagePath = $request->file('featured_image')->store('articles', 'public');
        $article->featured_image = $imagePath;
    }

    $article->update([
        'title' => $validated['title'],
        'category_id' => $validated['category_id'],
        'excerpt' => $validated['excerpt'],
        'content' => $validated['content'],
        'slug' => Str::slug($validated['title']) . '-' . time(),
    ]);

    $article->tags()->sync($request->tags ?? []);

    // FIX: Use $article->slug instead of $article
    return redirect()
        ->route('articles.show', $article->slug)
        ->with('success', 'Article updated successfully.');
}

    /**
     * Delete Article
     */
    public function destroy(Article $article)
    {
        if (!auth()->user()->isAuthor() || auth()->id() !== $article->user_id) {
            abort(403, 'You can only delete your own articles.');
        }

        // Delete featured image
        if ($article->featured_image) {
            \Storage::disk('public')->delete($article->featured_image);
        }

        // Detach tags
        $article->tags()->detach();

        $article->delete();

        return redirect()
            ->route('home')
            ->with('success', 'Article deleted successfully.');
    }
}