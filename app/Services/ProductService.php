<?php

namespace App\Services;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Repositories\Contracts\ProductInterface;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        protected ProductInterface $productRepository,
        protected TranslationService $translationService
    ) {}

    public function index(string $lang ,int $id)
    {
        $category = Category::findOrFail($id);
        return $this->productRepository->index($lang, $category);
    }

    public function shopProducts(string $lang ,int $id)
    {
        $shop = Shop::findOrFail($id);
        return $this->productRepository->shopProducts($lang ,$shop);
    }

    public function bannedProducts(string $lang)
    {
        return $this->productRepository->bannedProducts($lang);
    }

    public function search(int $id, string $lang, Request $request)
    {
        $category = Category::findOrFail($id);
        return $this->productRepository->search($category, $lang, $request);
    }

    public function show(string $lang ,int $id)
    {
        return $this->productRepository->show($lang ,$id);
    }

    public function store(CreateProductRequest $request)
    {
        $category = Category::findOrFail($request->category_id);
        $data = $this->translationService->CategoryLangs($category->id);
        if ($data['available_langs_count'] == 0 or empty($data['available_langs'])) {
            throw new \Exception("You cannot use this category for now because it is Missing translation, please waite for any translation to be available.", 500);
        }
        return $this->productRepository->store($request);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        Category::findOrFail($request->category_id);
        $product = Product::whereKey($id)->where('user_id', Auth::id())->firstOrFail();
        return $this->productRepository->update($request, $product);
    }

    public function ban(int $id)
    {
        $product = Product::findOrFail($id);
        return $this->productRepository->ban($product);
    }

    public function unban(int $id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        return $this->productRepository->unban($product);
    }

    public function deleteMine(int $id)
    {
        $product = Product::whereKey($id)->where('user_id', Auth::id())->firstOrFail();

        // هذا المنطق مُنَفى للإستخدام التجارى   $product->orders()->exists()
        if ($product->orders()->exists() or $product->carts()->exists()) {
            throw new \Exception(
                'Can not delete the product because it has related carts or orders. They have get deleted first.'
            );
        }
        // Storage::deleteDirectory('products/'.$product->id.'/');

        return $this->productRepository->delete($product);
    }

    public function delete(int $id)
    {
        $product = Product::findOrFail($id);
        
        // هذا المنطق مُنَفى للإستخدام التجارى  $product->orders()->exists()
        if ($product->orders()->exists() or $product->carts()->exists()) {
            throw new \Exception(
                'Can not delete the product because it has related carts or orders. They have get deleted first.'
            );
        }
        // Storage::deleteDirectory('products/'.$product->id.'/');

        return $this->productRepository->delete($product);
    }
}
