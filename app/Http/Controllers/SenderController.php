<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use Illuminate\Http\Request;

class SenderController extends Controller
{
    public function index()
    {
        $senders = Sender::all();

        return response()->json([
            'status' => 200,
            'message' => 'Users retrieved succesfully',
            'data' => $senders 
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
        ]);

        $senders  = Sender::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'User created succesfully',
            'data' => $senders 
        ], 201);
    }

    public function show($id)
    {
        $senders  = Sender::find($id);

        if (!$cs) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
                'data' => null
            ],404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'User retrieved succesfully',
            'data' => $senders 
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $senders  = Sender::find($id);

        if(!$senders ) {
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
        $senders ->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'User updated succesfully',
            'data' => $senders 
        ], 200);
    }

    public function destroy($id)
    {
        $senders = Sender::find($id);

        if(!$senders) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $senders ->delete();

        return response()->json([
            'status' => 200,
            'message' => 'User deleted succesfully',
            'data' => null
        ], 200);
    }
}