<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('author')->active()
            ->paginate(20);

        return response()->json($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['posts.create']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->validated());

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (! $post->is_active) {
            return response()->json('Post not found.', 404);
        }

        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post, Request $request)
    {
        if ($request->user()->cannot('update', $post)) {
            return response()->json('You are not authorized to update this post.', 403);
        }

        return response()->json(['posts.edit']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $response = $post->update($request->validated());

        return $response
            ? response()->json($post)
            : response()->json('Failed to update post.', 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Request $request)
    {
        if ($request->user()->cannot('delete', $post)) {
            return response()->json('You are not authorized to delete this post.', 403);
        }

        $post->delete();

        return response()->json('Post deleted successfully.');
    }
}
