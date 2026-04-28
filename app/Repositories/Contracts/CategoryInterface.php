<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

interface CategoryInterface
{
    public function index(string $lang);
    public function store(CreateCategoryRequest $request ,string $fileName);
    public function show(string $lang ,int $id);
    public function update(UpdateCategoryRequest $request , Category $category, string $fileName);
    public function delete(Category $category);
}