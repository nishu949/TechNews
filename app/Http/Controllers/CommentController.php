<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewCommentNotification;

class CommentController extends Controller
{
    /**
     * Store a new comment
     */
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        // Check if article is published
        if ($article->status !== 'published') {
            return redirect()->back()
                ->with('error', 'You cannot comment on unpublished articles.');
        }

        // Create the comment
        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
            'status' => 'pending' // Requires moderation
        ]);

        // =============================================
        // SEND EMAIL NOTIFICATION TO AUTHOR
        // =============================================
        try {
            $author = $article->author;
            
            // Don't send notification if commenter is the author
            if (Auth::id() !== $author->id) {
                Mail::to($author->email)
                    ->send(new NewCommentNotification($comment));
                
                \Log::info('Comment notification sent to: ' . $author->email);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Comment notification failed: ' . $e->getMessage());
        }

        return redirect()
            ->back()
            ->with('success', 'Comment posted and awaiting moderation!');
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment)
    {
        // Allow author or comment owner to delete
        if (Auth::id() !== $comment->user_id && Auth::id() !== $comment->article->user_id) {
            abort(403, 'You are not authorized to delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

    /**
     * Approve a comment (for authors)
     */
    public function approve(Comment $comment)
    {
        // Check if user is the author of the article
        if (Auth::id() !== $comment->article->user_id) {
            abort(403, 'You are not authorized to approve this comment.');
        }

        $comment->update(['status' => 'approved']);

        return back()->with('success', 'Comment approved successfully!');
    }
}