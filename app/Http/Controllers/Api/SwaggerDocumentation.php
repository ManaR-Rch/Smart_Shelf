<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Laravel API Documentation",
 *     description="Documentation de l'API Laravel pour la gestion des produits, rayons, ventes, et utilisateurs.",
 *     @OA\Contact(
 *         email="support@magana.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Serveur API principal"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerDocumentation
{
    // Ce fichier ne contient que les annotations générales, sans logique métier
}
