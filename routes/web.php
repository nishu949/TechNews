<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MigrationController;
use Illuminate\Support\Facades\Route;
use App\Mail\NewCommentNotification;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

// ======================== PUBLIC ROUTES ========================

// Homepage
Route::get('/', [ArticleController::class, 'index'])->name('home');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

// ======================== ARTICLE ROUTES ========================

Route::middleware('auth')->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
});

Route::get('/articles/search', [ArticleController::class, 'search'])->name('articles.search');

Route::middleware('auth')->group(function () {
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
});

Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::middleware('auth')->group(function () {
    Route::get('/articles/{article:slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article:slug}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article:slug}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

// ======================== CATEGORY ROUTES ========================

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// ======================== TAG ROUTES ========================

Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tags/{tag:slug}', [TagController::class, 'show'])->name('tags.show');

// ======================== COMMENT ROUTES ========================

Route::middleware('auth')->group(function () {
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// ======================== DASHBOARD & PROFILE ========================

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ======================== TEST EMAIL ROUTE ========================

Route::get('/test-email', function () {
    $comment = Comment::with(['user', 'article'])->first();
    
    if (!$comment) {
        return 'No comment found. Please create a comment first.';
    }
    
    try {
        Mail::to($comment->article->author->email)
            ->send(new NewCommentNotification($comment));
        
        return '✅ Email sent successfully to: ' . $comment->article->author->email;
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
})->middleware('auth');

<?php

// ======================== TEST ROUTE (TOP OF FILE) ========================
Route::get('/test', function () {
    return "✅ App is working! PHP version: " . phpversion();
});

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "✅ Database connected! Database: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "❌ Database error: " . $e->getMessage();
    }
});

// Then all your other routes...

// ======================== DEPLOYMENT SETUP ROUTES ========================
// REMOVE THESE ROUTES AFTER DEPLOYMENT!

Route::get('/debug', [MigrationController::class, 'debug']);
Route::get('/fix-permissions', [MigrationController::class, 'fixPermissions']);
Route::get('/run-migrations', [MigrationController::class, 'runMigrations']);
Route::get('/seed-database', [MigrationController::class, 'seedDatabase']);

// Include Breeze Auth routes
require __DIR__.'/auth.php';