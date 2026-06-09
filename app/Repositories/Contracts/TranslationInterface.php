<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\AddTranslationRequest;
use App\Http\Requests\RemoveTranslationRequest;
use App\Models\Category;
use App\Models\Product;

interface TranslationInterface
{
    public function categoryLangs(Category $category);
    public function addToCategory(Category $category, AddTranslationRequest $request);
    public function removeFromCategory(Category $category, RemoveTranslationRequest $request);
    public function productLangs(Product $product);
    public function addToProduct(Product $product, AddTranslationRequest $request);
    public function removeFromProduct(Product $product, RemoveTranslationRequest $request);
}
