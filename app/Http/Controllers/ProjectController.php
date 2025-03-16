<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProjectController extends Controller
{
    // Affichage public des projets
    public function index()
    {
        $projects = Project::where('is_visible', true)
            ->orderBy('order')
            ->get();

        return Inertia::render('Portfolio/Index', [
            'projects' => $projects
        ]);
    }

    // Affichage de l'interface d'administration des projets
    public function admin()
    {
        $projects = Project::orderBy('order')->get();

        return Inertia::render('Portfolio/Admin', [
            'projects' => $projects
        ]);
    }

    // Enregistrement d'un nouveau projet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specs' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean'
        ]);

        // Traitement de l'image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $validated['image_path'] = $path;
        }

        Project::create($validated);

        return redirect()->back();
    }

    // Mise à jour d'un projet existant
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specs' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean'
        ]);

        // Traitement de l'image
        if ($request->hasFile('image')) {
            // Suppression de l'ancienne image s'il en existe une
            if ($project->image_path) {
                Storage::disk('public')->delete($project->image_path);
            }
            $path = $request->file('image')->store('projects', 'public');
            $validated['image_path'] = $path;
        }

        $project->update($validated);

        return redirect()->back();
    }

    // Suppression d'un projet
    public function destroy(Project $project)
    {
        // Supprimer l'image associée s'il y en a une
        if ($project->image_path) {
            Storage::disk('public')->delete($project->image_path);
        }

        $project->delete();

        return redirect()->back();
    }
}
