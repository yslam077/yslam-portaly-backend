<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    public function index(string $slug): AnonymousResourceCollection
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        $comments = $article->comments()
            ->approved()
            ->with('user')
            ->latest()
            ->paginate(10);

        return CommentResource::collection($comments);
    }

    public function store(Request $request, string $slug): JsonResponse
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $article->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => __('messages.comment_submitted'),
            'data' => new CommentResource($comment->load('user')),
        ], 201);
    }
}
