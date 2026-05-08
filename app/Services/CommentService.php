<?php

namespace App\Services;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Repositories\Contracts\CommentInterface;

class CommentService
{
    public function __construct(
        protected CommentInterface $commentRepository
    ) {}

    public function store (CreateCommentRequest $request)
    {
        return $this->commentRepository->store($request);
    }

    public function update (UpdateCommentRequest $request, int $id)
    {
        $comment = Comment::findOrFail($id);
        return $this->commentRepository->update($request, $comment);
    }

    public function delete (int $id)
    {
        $comment = Comment::findOrFail($id);
        return $this->commentRepository->delete($comment);
    }
}
