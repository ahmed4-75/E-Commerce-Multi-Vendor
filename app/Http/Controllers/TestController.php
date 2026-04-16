<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/test",
     *     summary="Get test",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="test Swagger",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Successful operation Test"),
     *         )
     *     )
     * )
    */
    public function test()
    {
        return response()->json(['message' => 'Successful operation Test'],200);
    }
}
