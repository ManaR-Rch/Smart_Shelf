<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/ventes/stats",
     *     summary="Obtenir les statistiques de vente",
     *     tags={"Ventes"},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques de vente récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="most_sold_products", type="array", @OA\Items(
     *                 @OA\Property(property="product_id", type="integer"),
     *                 @OA\Property(property="total_sold", type="integer"),
     *                 @OA\Property(property="product", type="object", 
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="quantity", type="integer")
     *                 )
     *             )),
     *             @OA\Property(property="critical_stock_products", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="quantity", type="integer")
     *             ))
     *         )
     *     )
     * )
     */
    public function getStats()
    {
        $mostSoldProducts = Order::select('product_id', DB::raw('COUNT(*) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->with(['product' => function ($query) {
                $query->select('id', 'name', 'quantity');
            }])
            ->get();

        $criticalStockProducts = Product::where('quantity', '<=', 1)
            ->orderBy('quantity')
            ->get(['id', 'name', 'quantity']);

        return response()->json([
            'most_sold_products' => $mostSoldProducts,
            'critical_stock_products' => $criticalStockProducts,
        ]);
    }
}
