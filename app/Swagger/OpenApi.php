<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="E-commerce API",
 *     version="1.0.0",
 *     description="API documentation for E-commerce"
 * )
 *
 * @OA\Server(
 *     url="http://localhost/Ecommerce/public",
 *     description="Local server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class OpenApi {}