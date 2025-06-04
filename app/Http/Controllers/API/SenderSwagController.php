<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sender;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Senders",
 *     description="API Endpoints for Managing Senders"
 * )
 */
class SenderSwagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/senders",
     *     summary="Get all senders",
     *     tags={"Senders"},
     *     @OA\Response(
     *         response=200,
     *         description="Senders retrieved successfully"
     *     )
     * )
     */
    public function index()
    {
        $senders = Sender::all();

        return response()->json([
            'status' => 200,
            'message' => 'Users retrieved successfully',
            'data' => $senders
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/senders",
     *     summary="Create a new sender",
     *     tags={"Senders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone_number", "address"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="08123456789"),
     *             @OA\Property(property="address", type="string", example="Jl. Merdeka No.1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sender created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
        ]);

        $sender = Sender::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'User created successfully',
            'data' => $sender
        ], 201);
    }


    /**
     * @OA\Put(
     *     path="/senders/{id}",
     *     summary="Update a sender",
     *     tags={"Senders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Sender ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone_number", "address"},
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", example="jane@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="08129876543"),
     *             @OA\Property(property="address", type="string", example="Jl. Sudirman No.2")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sender updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sender not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $sender = Sender::find($id);

        if (!$sender) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
        ]);

        $sender->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully',
            'data' => $sender
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/senders/{id}",
     *     summary="Delete a sender",
     *     tags={"Senders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Sender ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sender deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sender not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $sender = Sender::find($id);

        if (!$sender) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $sender->delete();

        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully',
            'data' => null
        ], 200);
    }
}
