<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rayon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Liste de tous les produits",
     *     tags={"Products"},
     *     @OA\Response(response=200, description="Liste des produits")
     * )
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Obtenir les détails d'un produit",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du produit",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Détails du produit"),
     *     @OA\Response(response=404, description="Produit non trouvé")
     * )
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Ajouter un nouveau produit",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="quantity", type="integer"),
     *             @OA\Property(property="rayon_id", type="integer"),
     *             @OA\Property(property="category", type="string"),
     *             @OA\Property(property="is_promoted", type="boolean"),
     *             @OA\Property(property="popularity", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Produit créé avec succès"),
     *     @OA\Response(response=400, description="Erreur de validation")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'rayon_id' => 'required|exists:rayons,id',
            'category' => 'required|string',
            'is_promoted' => 'boolean',
            'popularity' => 'integer',
        ]);

        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Mettre à jour un produit",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du produit",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="quantity", type="integer"),
     *             @OA\Property(property="rayon_id", type="integer"),
     *             @OA\Property(property="category", type="string"),
     *             @OA\Property(property="is_promoted", type="boolean"),
     *             @OA\Property(property="popularity", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Produit mis à jour"),
     *     @OA\Response(response=404, description="Produit non trouvé")
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update($request->all());
        return response()->json($product);
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Supprimer un produit",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du produit",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Produit supprimé"),
     *     @OA\Response(response=404, description="Produit non trouvé")
     * )
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product removed']);
    }

    /**
     * @OA\Get(
     *     path="/products/search",
     *     summary="Rechercher des produits",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Terme de recherche",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Résultats de la recherche")
     * )
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', "%$query%")
            ->orWhere('category', 'like', "%$query%")
            ->get();
        return response()->json($products);
    }

    /**
     * @OA\Get(
     *     path="/rayons/{rayonId}/products",
     *     summary="Obtenir les produits d'un rayon",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="rayonId",
     *         in="path",
     *         description="ID du rayon",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Produits du rayon"),
     *     @OA\Response(response=404, description="Rayon non trouvé")
     * )
     */
    public function getProductsInRayon($rayonId)
    {
        $rayon = Rayon::find($rayonId);
        if (!$rayon) {
            return response()->json(['message' => 'Rayon not found'], 404);
        }

        $products = Product::where('rayon_id', $rayonId)->get();
        return response()->json($products);
    }

    /**
     * @OA\Get(
     *     path="/products/alert",
     *     summary="Alerte sur les stocks bas",
     *     tags={"Products"},
     *     @OA\Response(response=200, description="Liste des produits nécessitant un réapprovisionnement")
     * )
     */
    public function getAlert()
    {
        $lowThresholdProducts = Product::where('quantity', '<=', 10)->get(['id', 'name', 'quantity']);
        return response()->json(['message' => 'ATTENTION: These products need restocking.', 'products' => $lowThresholdProducts]);
    }
}
