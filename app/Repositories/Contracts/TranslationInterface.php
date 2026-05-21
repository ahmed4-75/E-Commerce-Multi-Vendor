<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\AddTranslationRequest;
use App\Models\Category;
use App\Models\Product;

interface TranslationInterface
{
    public function categoryLangs(Category $category);
    public function addToCategory(Category $category, AddTranslationRequest $request);
    public function productLangs(Product $product);
    public function addToProduct(Product $product, AddTranslationRequest $request);
}
