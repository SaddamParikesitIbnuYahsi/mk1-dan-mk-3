<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Sender",
 *     title="Sender",
 *     description="Sender model",
 *     type="object",
 *     required={"id", "name"},
 *     
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Sender Name")
 * )
 */
class Sender {}
