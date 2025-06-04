<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Shipment",
 *     title="Shipment",
 *     description="Shipment model",
 *     type="object",
 *     required={"id", "tracking_number", "sender_name", "receiver_name", "status", "created_at"},
 *     
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tracking_number", type="string", example="TRK123456789"),
 *     @OA\Property(property="sender_name", type="string", example="John Doe"),
 *     @OA\Property(property="receiver_name", type="string", example="Jane Smith"),
 *     @OA\Property(property="status", type="string", example="In Transit"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-03T10:15:30Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-03T12:00:00Z")
 * )
 */
class Shipment {}
