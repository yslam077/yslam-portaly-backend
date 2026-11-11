<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Support\PublicCache;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = Comment::with(['user', 'article']);

        if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $comments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'approved']);
        PublicCache::flush();

        return back()->with('success', 'Комментарий одобрен.');
    }

    public function reject(Comment $comment)
    {
        $comment->update(['status' => 'rejected']);
        PublicCache::flush();

        return back()->with('success', 'Комментарий отклонён.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        PublicCache::flush();

        return back()->with('success', 'Комментарий удалён.');
    }
}