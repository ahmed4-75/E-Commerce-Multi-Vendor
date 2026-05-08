<?php

namespace App\Services;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Contracts\ProductInterface;
// use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        protected ProductInterface $productRepository
    ) {}

    public function index(string $lang ,int $id)
    {
        return $this->productRepository->index($lang ,$id);
    }

    public function store(CreateProductRequest $request)
    {
        return $this->productRepository->store($request);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $product = Product::findOrFail($id);

        return $this->productRepository->update($request, $product);
    }

    public function show(string $lang ,int $id)
    {
        return $this->productRepository->show($lang ,$id);
    }

    public function delete(int $id)
    {
        $product = Product::findOrFail($id);

        // Storage::deleteDirectory('products/'.$product->id.'/');

        return $this->productRepository->delete($product);
    }
}
