<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    /**
     * Créer un nouveau tag
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags',
            'slug' => 'nullable|string|max:255|unique:tags',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Tag::create($validated);

        // Vider les caches concernés
        Cache::forget('all_tags');
        Cache::forget('blog_posts_index');

        return redirect()->back()->with('success', 'Tag créé avec succès');
    }

    /**
     * Supprimer un tag
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        // Vider les caches concernés
        Cache::forget('all_tags');
        Cache::forget('blog_posts_index');

        return redirect()->back()->with('success', 'Tag supprimé avec succès');
    }
}
