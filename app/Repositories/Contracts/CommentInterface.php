<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;

interface CommentInterface
{
    public function store(CreateCommentRequest $request);
    public function update(UpdateCommentRequest $request, Comment $comment);
    public function delete(Comment $comment);
}
