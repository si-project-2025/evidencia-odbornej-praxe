<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            ['id' => 1, 'title' => 'First Post', 'content' => 'Lorem ipsum'],
            ['id' => 2, 'title' => 'Second Post', 'content' => 'Dolor sit amet'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation and saving to DB
        $data = $request->all();
        return response()->json(['message' => 'Post created', 'data' => $data]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(['id' => $id, 'title' => "Post $id", 'content' => "Content for post $id"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        return response()->json(['message' => "Post $id updated", 'data' => $data]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['message' => "Post $id deleted"]);
    }
}
