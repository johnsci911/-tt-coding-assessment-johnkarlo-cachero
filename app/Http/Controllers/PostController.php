<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tag_id' => 'required|exists:tags,id',
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = Post::create([
            'tag_id' => $request->tag_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Attach tags to post
        $post->tags()->attach($request->tag_id);

        return response()->json($post, 201);
    }
}
