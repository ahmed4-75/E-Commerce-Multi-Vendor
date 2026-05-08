<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

interface ProductInterface
{
    public function index(string $lang ,int $id);
    public function show(string $lang ,int $id);
    public function store(CreateProductRequest $request);
    public function update(UpdateProductRequest $request, Product $product);
    public function delete(Product $product);
}
