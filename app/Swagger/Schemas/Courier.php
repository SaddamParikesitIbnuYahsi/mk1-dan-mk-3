<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Courier",
 *     title="Courier",
 *     description="Courier model",
 *     type="object",
 *     required={"id", "name"},
 *     
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Courier Name")
 * )
 */
class Courier {}
