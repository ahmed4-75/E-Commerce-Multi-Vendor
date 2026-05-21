<?php

namespace App\Repositories;

use App\Enums\LanguagesEnum;
use App\Http\Requests\AddTranslationRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\TranslationInterface;

class TranslationRepository implements TranslationInterface
{
    public function categoryLangs(Category $category)
    {
        $availableLangs = $category->translations()->pluck('lang')->toArray();
        $availableLangs_count = count($availableLangs);
        $unavailableLangs = array_values(array_diff(LanguagesEnum::values(),$availableLangs));
        $unavailableLangs_count = count($unavailableLangs);
        return [
            'available_langs' => $availableLangs,
            'available_langs_count' => $availableLangs_count,
            'unavailable_langs' => $unavailableLangs,
            'unavailable_langs_count' => $unavailableLangs_count
        ];
    }

    public function addToCategory(Category $category, AddTranslationRequest $request)
    {
        $category->translations()->create
        ([
            'name' => $request->name,
            'description' => $request->description,
            'lang' => $request->lang
        ]);
        return true;
    }

    public function productLangs(Product $product)
    {
        $availableLangs = $product->translations()->pluck('lang')->toArray();
        $availableLangs_count = count($availableLangs);
        $unavailableLangs = array_values(array_diff(LanguagesEnum::values(),$availableLangs));
        $unavailableLangs_count = count($unavailableLangs);
        return [
            'available_langs' => $availableLangs,
            'available_langs_count' => $availableLangs_count,
            'unavailable_langs' => $unavailableLangs,
            'unavailable_langs_count' => $unavailableLangs_count
        ];
    }

    public function addToProduct( Product $product, AddTranslationRequest $request)
    {
        $product->translations()->create
        ([
            'name' => $request->name,
            'description' => $request->description,
            'lang' => $request->lang
        ]);
        return true;
    }
}
