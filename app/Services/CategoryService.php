<?php

namespace App\Services;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\Contracts\CategoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        protected CategoryInterface $categoryRepository
    ) {}

    public function index(string $lang)
    {
        return $this->categoryRepository->index($lang);
    }

    public function store(CreateCategoryRequest $request)
    {
        $file = $request->file('image_path');
        $fileName = Str::slug($request->name)."_category.".$file->getClientOriginalExtension();
        $file->storeAs("categories",$fileName);
        return $this->categoryRepository->store($request, $fileName);
    }

    public function show(string $lang ,int $id)
    {
        return $this->categoryRepository->show($lang ,$id);
    }

    public function update(UpdateCategoryRequest $request ,int $id)
    {  
        $category = Category::FindOrFail($id);

        Storage::delete('categories/'.$category->image_path);
        $file = $request->file('image_path');
        $fileName = Str::slug($request->name)."_category.".$file->getClientOriginalExtension();
        $file->storeAs("categories",$fileName);

        return $this->categoryRepository->update($request, $category, $fileName);
    }

    public function delete(int $id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->exists()) {
            throw new \Exception(
                'Can not delete the category because it has related products. Move the products or delete them.'
            );
        }
        return $this->categoryRepository->delete($category);
        return true;
    }
}