<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        return redirect()->back();
    }

    /**
     * Supprimer un tag
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->back();
    }
}
