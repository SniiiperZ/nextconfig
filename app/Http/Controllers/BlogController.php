<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BlogController extends Controller
{
    /**
     * Affiche la liste des articles de blog (frontend)
     */
    public function index()
    {
        $posts = Cache::remember('blog_posts_index', 60 * 10, function () {
            return BlogPost::published()
                ->with('tags:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'featured_image', 'content', 'created_at', 'updated_at')
                ->orderByDesc('created_at')
                ->get();
        });

        return Inertia::render('Blog/Index', [
            'posts' => $posts
        ]);
    }

    /**
     * Affiche un article de blog spécifique (frontend)
     */
    public function show($slug)
    {
        $post = Cache::remember('blog_post_' . $slug, 60 * 10, function () use ($slug) {
            $post = BlogPost::published()
                ->with(['tags:id,name,slug', 'approvedComments'])
                ->where('slug', $slug)
                ->firstOrFail();

            // Récupérer les articles connexes basés sur les tags
            $relatedPosts = collect();
            if ($post->tags->count() > 0) {
                $tagIds = $post->tags->pluck('id');
                $relatedPosts = BlogPost::published()
                    ->whereHas('tags', function ($query) use ($tagIds) {
                        $query->whereIn('tags.id', $tagIds);
                    })
                    ->where('id', '!=', $post->id)
                    ->with('tags:id,name,slug')
                    ->select('id', 'title', 'slug', 'excerpt', 'featured_image', 'created_at')
                    ->orderByDesc('created_at')
                    ->limit(3)
                    ->get();
            }

            return [
                'post' => $post,
                'relatedPosts' => $relatedPosts,
                'comments' => $post->approvedComments
            ];
        });

        return Inertia::render('Blog/Show', $post);
    }

    /**
     * Affiche le panneau d'administration des articles
     */
    public function admin()
    {
        $posts = BlogPost::with('tags:id,name')
            ->orderByDesc('created_at')
            ->get();

        $tags = Cache::remember('all_tags', 60 * 30, function () {
            return Tag::orderBy('name')->get();
        });

        return Inertia::render('Blog/Admin', [
            'posts' => $posts,
            'tags' => $tags
        ]);
    }

    /**
     * Créer un nouvel article
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'order' => 'required|integer',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Traiter l'image mise en avant
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        // Créer l'article
        $post = BlogPost::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_image' => $validated['featured_image'] ?? null,
            'is_published' => $validated['is_published'] ?? false,
            'order' => $validated['order'],
        ]);

        // Associer les tags
        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        // Vider les caches concernés
        $this->clearBlogCaches();

        return redirect()->back();
    }

    /**
     * Mettre à jour un article existant
     */
    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'order' => 'required|integer',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'delete_image' => 'boolean'
        ]);

        // Gérer le slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Gérer l'image
        if ($validated['delete_image'] ?? false) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = null;
        }

        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        // Mettre à jour l'article
        $post->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_image' => $validated['featured_image'] ?? $post->featured_image,
            'is_published' => $validated['is_published'] ?? false,
            'order' => $validated['order'],
        ]);

        // Mettre à jour les tags
        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        // Vider les caches concernés
        $this->clearBlogCaches($post->slug);

        return redirect()->back();
    }

    /**
     * Supprimer un article
     */
    public function destroy(BlogPost $post)
    {
        // Supprimer l'image si elle existe
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        // Récupérer le slug avant suppression pour vider le cache
        $slug = $post->slug;

        $post->delete();

        // Vider les caches concernés
        $this->clearBlogCaches($slug);

        return redirect()->back();
    }

    /**
     * Vider les caches liés au blog
     */
    private function clearBlogCaches($slug = null)
    {
        Cache::forget('blog_posts_index');
        Cache::forget('blog_posts_count');

        if ($slug) {
            Cache::forget('blog_post_' . $slug);
        }
    }
}
