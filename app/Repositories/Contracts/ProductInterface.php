<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

interface ProductInterface
{
    public function index(string $lang ,Category $category);
    public function shopProducts(string $lang ,Shop $shop);
    public function bannedProducts(string $lang);
    public function search(Category $category, string $lang, Request $request);
    public function show(string $lang ,int $id);
    public function store(CreateProductRequest $request);
    public function update(UpdateProductRequest $request, Product $product);
    public function ban(Product $product);
    public function unban(Product $product);
    public function delete(Product $product);
}
