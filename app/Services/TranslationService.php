<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\AddTranslationRequest;
use App\Http\Requests\RemoveTranslationRequest;
use App\Repositories\Contracts\TranslationInterface;
use Illuminate\Support\Facades\Auth;

class TranslationService
{
    public function __construct(
        protected TranslationInterface $translationRepository,
    ) {}

    public function categoryLangs(int $id)
    {
        $category = Category::findOrFail($id);
        return $this->translationRepository->categoryLangs($category);
    }

    public function addToCategory(int $id, AddTranslationRequest $request)
    {
        $category = Category::findOrFail($id);
        $available_langs = $this->translationRepository->categoryLangs($category)['available_langs'];
        foreach ($available_langs as $lang) {
            if ($request->lang === $lang) {
                throw new \Exception("This language already exists for this category.", 400);
            }
        }
        return $this->translationRepository->addToCategory($category, $request);
    }

    public function removeFromCategory(int $id, RemoveTranslationRequest $request)
    {
        $category = Category::findOrFail($id);
        return $this->translationRepository->removeFromCategory($category, $request);
    }

    public function productLangs(int $id)
    {
        $product = Product::whereKey($id)->where('user_id', Auth::id())->firstOrFail();
        return $this->translationRepository->productLangs($product);
    }

    public function addToProduct(int $id, AddTranslationRequest $request)
    {
        $product = Product::whereKey($id)->where('user_id', Auth::id())->firstOrFail();
        $available_langs = $this->translationRepository->productLangs($product)['available_langs'];
        foreach ($available_langs as $lang) {
            if ($request->lang === $lang) {
                throw new \Exception("This language already exists for this product.", 400);
            }
        }
        return $this->translationRepository->addToProduct($product, $request);
    }

    public function removeFromProduct(int $id, RemoveTranslationRequest $request)
    {
        $product = Product::whereKey($id)->where('user_id', Auth::id())->firstOrFail();
        return $this->translationRepository->removeFromProduct($product, $request);
    }
}
