<?php

namespace App\Repositories;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Repositories\Contracts\CommentInterface;
use Illuminate\Support\Facades\Auth;

class CommentRepository implements CommentInterface
{
    public function store(CreateCommentRequest $request)
    {
        Comment::create([
            'content' => $request->content,
            'lang' => $request->lang,
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);
    }

    public function update (UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update(['content' => $request->content]);
    }

    public function delete (Comment $comment)
    {
        return Comment::destroy($comment->id);
    }
}
