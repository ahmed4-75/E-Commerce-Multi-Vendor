<?php

namespace App\Repositories;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\Contracts\CategoryInterface;
use Illuminate\Support\Facades\Storage;

class CategoryRepository implements CategoryInterface
{
    public function index(string $lang)
    {
        return Category::whereHas('translations', function ($q) use ($lang) { $q->where('lang', $lang); })
            ->with([ 'translations' => function ($q) use ($lang) { $q->where('lang', $lang); }])
        ->get();
    }

    public function store(CreateCategoryRequest $request ,string $fileName)
    {
        $category = Category::create([ 'image_path' => $fileName ]);

        $category->translations()->create
        ([
            'name' => $request->name,
            'description' => $request->description,
            'lang' => $request->lang
        ]);
    }

    public function show(string $lang ,int $id)
    {
        return Category::where('id',$id)->whereHas('translations', function ($q) use ($lang) { $q->where('lang', $lang); })
            ->with([ 'translations' => function ($q) use ($lang) { $q->where('lang', $lang); } ])
        ->first();
    }

    public function update(UpdateCategoryRequest $request , Category $category, string $fileName)
    {
        $category->update([ 'image_path' => $fileName ]);

        $category->translations()->where('id', $request->translation_id)->update
        ([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return $category;
    }


    public function delete(Category $category)
    {    
        Storage::delete('categories/'.$category->image_path);
        $category->translations()->delete();
        $category->delete();
        return true;
    }
}