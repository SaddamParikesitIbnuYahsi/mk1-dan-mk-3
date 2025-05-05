<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Order",
 *     title="Order",
 *     description="Order model",
 *     type="object",
 *     required={"id", "name"},
 *     
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Order Name")
 * )
 */
class Order {}
