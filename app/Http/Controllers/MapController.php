<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Affiche la page de la carte
     */
    public function index()
    {
        // Coordonnées par défaut (Lyon, France)
        $defaultLatitude = 45.764043;
        $defaultLongitude = 4.835659;
        $defaultZoom = 13;

        // Récupération des objectifs avec des coordonnées géographiques
        $goals = Goal::withLocation();
        
        // Ajouter le filtre par utilisateur seulement si l'utilisateur est connecté
        if (auth()->check()) {
            $goals = $goals->where('user_id', auth()->id());
        }
        
        $goals = $goals->get();
        
        // Transformer les objectifs en marqueurs pour la carte
        $markers = $goals->map(function($goal) {
            // Vérifier que les coordonnées sont valides
            $lat = is_numeric($goal->lat) ? $goal->lat : null;
            $lng = is_numeric($goal->lng) ? $goal->lng : null;
            
            return [
                'id' => $goal->id,
                'title' => $goal->title,
                'status' => $goal->status,
                'location' => $goal->location,
                'latitude' => $lat,
                'longitude' => $lng,
                'progress' => $goal->progress ?? 0,
                'date' => $goal->updated_at->format('Y-m-d'),
                'description' => $goal->description
            ];
        });

        return view('carteGeo', compact('defaultLatitude', 'defaultLongitude', 'defaultZoom', 'markers'));
    }
    
    /**
     * Sauvegarde un nouvel objectif
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,completed,archived',
            'location' => 'nullable|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'progress' => 'nullable|integer|min:0|max:100'
        ]);
        
        // Créer le nouvel objectif
        $goal = new Goal();
        $goal->user_id = auth()->id();
        $goal->title = $validated['title'];
        $goal->status = $validated['status'];
        $goal->location = $validated['location'];
        $goal->lat = $validated['lat'];
        $goal->lng = $validated['lng'];
        $goal->description = $validated['description'];
        $goal->progress = $validated['progress'] ?? 0;
        $goal->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Objectif créé avec succès',
            'goal' => $goal
        ]);
    }
    
    /**
     * Supprime un objectif existant
     */
    public function destroy($id)
    {
        $goal = Goal::where('id', $id);
        
        // Vérifier que l'utilisateur est propriétaire de l'objectif
        if (auth()->check()) {
            $goal = $goal->where('user_id', auth()->id());
        }
        
        $goal = $goal->first();
        
        if (!$goal) {
            return response()->json([
                'success' => false,
                'message' => 'Objectif non trouvé'
            ], 404);
        }
        
        $goal->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Objectif supprimé avec succès'
        ]);
    }
}