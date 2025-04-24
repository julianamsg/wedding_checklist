<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(title="Wedding Checklist", version="0.1")
 *
 * @OA\SecurityScheme(
 * type="http", 
 * description="Access token autentication",
 * name="Authorization",
 * in="header",
 * scheme="bearer",
 * bearerFormat="JWT",
 * securityScheme="bearerAuth")
 */
abstract class Controller
{
    //
}
