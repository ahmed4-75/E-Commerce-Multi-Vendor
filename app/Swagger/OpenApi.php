<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="E-Commerce API",
 *     version="1.0.0",
 *     description="API documentation for E-Commerce"
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
