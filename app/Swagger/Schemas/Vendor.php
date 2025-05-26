<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Vendor",
 *     title="Vendor",
 *     description="Vendor model",
 *     type="object",
 *     required={"id", "name"},
 *     
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Vendor Name")
 * )
 */
class Vendor {}
