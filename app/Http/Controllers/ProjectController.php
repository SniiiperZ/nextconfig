<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProjectController extends Controller
{
    // Affichage public des projets avec leurs images
    public function index()
    {
        $projects = Project::where('is_visible', true)
            ->orderBy('order')
            ->with('images') // Charger toutes les images associées
            ->get();

        return Inertia::render('Portfolio/Index', [
            'projects' => $projects
        ]);
    }

    // Affichage de l'interface d'administration des projets
    public function admin()
    {
        $projects = Project::with('images') // Charger les images pour chaque projet
            ->orderBy('order')
            ->get();

        return Inertia::render('Portfolio/Admin', [
            'projects' => $projects
        ]);
    }

    // Enregistrement d'un nouveau projet avec images
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specs' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
        ]);

        // Création du projet
        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'specs' => $validated['specs'] ?? null,
            'order' => $validated['order'],
            'is_featured' => $validated['is_featured'] ?? false,
            'is_visible' => $validated['is_visible'] ?? true
        ]);

        // Traitement des images
        if ($request->hasFile('images')) {
            $this->handleProjectImages($request->file('images'), $project);
        }

        return redirect()->back();
    }

    // Mise à jour d'un projet existant avec ses images
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specs' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
        ]);

        // Mise à jour des informations du projet
        $project->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'specs' => $validated['specs'] ?? null,
            'order' => $validated['order'],
            'is_featured' => $validated['is_featured'] ?? false,
            'is_visible' => $validated['is_visible'] ?? true
        ]);

        // Suppression des images sélectionnées
        if (!empty($validated['delete_images'])) {
            foreach ($validated['delete_images'] as $imageId) {
                $image = ProjectImage::find($imageId);
                if ($image && $image->project_id == $project->id) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }

            // Après suppression, mettre à jour l'image principale
            $this->updateMainImage($project);
        }

        // Ajout de nouvelles images
        if ($request->hasFile('images')) {
            $this->handleProjectImages($request->file('images'), $project);
        }

        return redirect()->back();
    }

    // Suppression d'un projet et toutes ses images associées
    public function destroy(Project $project)
    {
        // Supprimer toutes les images associées
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Supprimer également l'ancienne image principale si elle existe
        if ($project->image_path) {
            Storage::disk('public')->delete($project->image_path);
        }

        $project->delete();

        return redirect()->back();
    }

    /**
     * Gestion des uploads d'images pour un projet - Version simplifiée
     * @param array $images
     * @param Project $project
     */
    private function handleProjectImages(array $images, Project $project)
    {
        $isFirstUpload = $project->images()->count() === 0;
        $uploadCount = 0;

        foreach ($images as $index => $image) {
            $path = $image->store('projects', 'public');

            // Créer l'entrée dans la table project_images
            $projectImage = new ProjectImage([
                'image_path' => $path,
                'order' => $project->images()->count() + $uploadCount,
                'is_main' => false // Par défaut, pas d'image principale
            ]);

            $project->images()->save($projectImage);
            $uploadCount++;
        }

        // Mettre à jour l'image principale après avoir traité toutes les images
        $this->updateMainImage($project);
    }

    /**
     * Met à jour l'image principale du projet (utilise toujours la première image)
     * @param Project $project
     */
    private function updateMainImage(Project $project)
    {
        // Réinitialiser toutes les images comme non-principales
        $project->images()->update(['is_main' => false]);

        // Définir la première image (par ordre) comme principale
        $firstImage = $project->images()->orderBy('order')->first();

        if ($firstImage) {
            $firstImage->is_main = true;
            $firstImage->save();

            // Mettre à jour également le champ image_path du projet pour compatibilité
            $project->image_path = $firstImage->image_path;
            $project->save();
        } else {
            // Pas d'image, réinitialiser image_path
            $project->image_path = null;
            $project->save();
        }
    }

    /**
     * Mettre à jour l'ordre des images d'un projet
     */
    public function updateImageOrder(Request $request, Project $project)
    {
        try {
            $validated = $request->validate([
                'images' => 'required|array',
                'images.*.id' => 'required|integer',
                'images.*.order' => 'required|integer'
            ]);

            foreach ($validated['images'] as $imageData) {
                $image = ProjectImage::find($imageData['id']);
                if ($image && $image->project_id == $project->id) {
                    $image->order = $imageData['order'];
                    $image->save();
                }
            }

            // Après réorganisation, mettre à jour l'image principale
            $this->updateMainImage($project);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
