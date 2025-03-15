<?php

namespace App\Http\Controllers;

use App\Models\Rayon;
use Illuminate\Http\Request;

class RayonController extends Controller
{
    /**
     * @OA\Get(
     *     path="/rayons",
     *     summary="Liste de tous les rayons",
     *     tags={"Rayons"},
     *     @OA\Response(response=200, description="Liste des rayons")
     * )
     */
    public function index()
    {
        $rayons = Rayon::all();
        return response()->json($rayons);
    }

    /**
     * @OA\Get(
     *     path="/rayons/{id}",
     *     summary="Obtenir les détails d'un rayon",
     *     tags={"Rayons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du rayon",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Détails du rayon"),
     *     @OA\Response(response=404, description="Rayon non trouvé")
     * )
     */
    public function show($id)
    {
        $rayon = Rayon::find($id);
        if (!$rayon) {
            return response()->json(['message' => 'Rayon not found'], 404);
        }
        return response()->json($rayon);
    }

    /**
     * @OA\Post(
     *     path="/rayons",
     *     summary="Ajouter un nouveau rayon",
     *     tags={"Rayons"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Rayon créé avec succès"),
     *     @OA\Response(response=400, description="Erreur de validation")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $rayon = Rayon::create($request->all());
        return response()->json($rayon, 201);
    }

    /**
     * @OA\Put(
     *     path="/rayons/{id}",
     *     summary="Mettre à jour un rayon",
     *     tags={"Rayons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du rayon",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Rayon mis à jour"),
     *     @OA\Response(response=404, description="Rayon non trouvé")
     * )
     */
    public function update(Request $request, $id)
    {
        $rayon = Rayon::find($id);
        if (!$rayon) {
            return response()->json(['message' => 'Rayon not found'], 404);
        }

        $rayon->update($request->all());
        return response()->json($rayon);
    }

    /**
     * @OA\Delete(
     *     path="/rayons/{id}",
     *     summary="Supprimer un rayon",
     *     tags={"Rayons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du rayon",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Rayon supprimé"),
     *     @OA\Response(response=404, description="Rayon non trouvé")
     * )
     */
    public function destroy($id)
    {
        $rayon = Rayon::find($id);
        if (!$rayon) {
            return response()->json(['message' => 'Rayon not found'], 404);
        }

        $rayon->delete();
        return response()->json(['message' => 'Rayon removed']);
    }
}
