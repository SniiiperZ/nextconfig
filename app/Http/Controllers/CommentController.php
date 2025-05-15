<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class CommentController extends Controller
{
    /**
     * Afficher la page d'administration des commentaires
     */
    public function index()
    {
        $pendingComments = Comment::getPendingComments();
        $approvedComments = Comment::getApprovedComments(20);

        return Inertia::render('Blog/Comments', [
            'pendingComments' => $pendingComments,
            'approvedComments' => $approvedComments,
            'pendingCount' => Comment::getPendingCount()
        ]);
    }

    /**
     * Enregistrer un nouveau commentaire
     */
    public function store(Request $request, $slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|max:1000',
        ]);

        $post->comments()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'content' => $validated['content'],
            'is_approved' => false, // Par défaut, tous les commentaires nécessitent une modération
        ]);

        // Vider les caches concernés
        Cache::forget('blog_post_' . $slug);
        Cache::forget('pending_comments_count');
        Cache::forget('pending_comments');

        return redirect()->back()->with('success', 'Votre commentaire a été soumis et sera visible après modération.');
    }

    /**
     * Approuver un commentaire
     */
    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true]);

        // Vider les caches concernés
        if ($comment->blogPost) {
            Cache::forget('blog_post_' . $comment->blogPost->slug);
        }
        Cache::forget('pending_comments_count');
        Cache::forget('approved_comments');
        Cache::forget('pending_comments');

        return redirect()->back()->with('success', 'Commentaire approuvé avec succès.');
    }

    /**
     * Rejeter/supprimer un commentaire
     */
    public function destroy(Comment $comment)
    {
        $slug = $comment->blogPost?->slug;

        $comment->delete();

        // Vider les caches concernés
        if ($slug) {
            Cache::forget('blog_post_' . $slug);
        }
        Cache::forget('pending_comments_count');
        Cache::forget('approved_comments');
        Cache::forget('pending_comments');

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
    }
}
