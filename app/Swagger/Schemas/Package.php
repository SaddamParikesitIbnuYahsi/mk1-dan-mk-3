<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Package",
 *     title="Package",
 *     description="Package model",
 *     type="object",
 *     required={"id", "name"},
 *     
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Package Name")
 * )
 */
class Package {}
