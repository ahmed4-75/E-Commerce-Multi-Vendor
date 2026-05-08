<?php

namespace App\Repositories;

use App\Repositories\Contracts\ProductInterface;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;

class ProductRepository implements ProductInterface
{
    public function index(string $lang ,int $id)
    {
        return Product::query()->where('category_id', $id)->whereHas('translations', function ($q) use ($lang) { $q->where('lang', $lang); })
            ->with([ 'translations' => function ($q) use ($lang) { $q->where('lang', $lang); },'productImages'])
        ->paginate(9);
    }
    public function show(string $lang ,int $id)
    {
        $product = Product::query()->whereKey($id)->whereHas('translations', function ($q) use ($lang) { $q->where('lang', $lang); })
            ->with([ 'translations' => function ($q) use ($lang) { $q->where('lang', $lang); },'productImages','shop'])
        ->firstOrFail();

        $comments = $product->comments()->where('lang', $lang)->with('user')->orderByRaw('user_id = ? DESC', [Auth::id() ?? 0])->orderBy('created_at', 'desc')->paginate(10);

        return $data = [
            'product' => $product,
            'comments' => $comments
        ];
    }

    public function store(CreateProductRequest $request)
    {
        $product = Product::create([
            'quantity' => $request->quantity,
            'price' => $request->price,
            'discount' => $request->discount,
            'category_id' => $request->category_id,
            'shop_id' => $request->shop_id,
            'user_id' => Auth::id(),
        ]);

        $product->translations()->create([
            'name' => $request->name,
            'description' => $request->description,
            'lang' => $request->lang,
        ]);

        // foreach ($request->input('images', []) as $index => $imageData) {
        //     $pageName = $imageData['page_name'];
        //     $imageFile = $request->file("images.$index.image_path");
        //     $fileName = Str::slug($request->name).'_product_'.$pageName.'.'.$imageFile->getClientOriginalExtension();

        //     $imageFile->storeAs('products/'.$product->id.'/', $fileName);

        //     $product->productImages()->create([
        //         'page_name' => $pageName,
        //         'image_path' => $fileName,
        //     ]);
        // }
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'quantity' => $request->quantity,
            'price' => $request->price,
            'discount' => $request->discount,
            'category_id' => $request->category_id,
            'shop_id' => $request->shop_id,
        ]);

        $product->translations()->where('id', $request->translation_id)->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // $newImages = collect($request->input('images', []))->keyBy('page_name');
        // $oldImages = $product->productImages()->get()->keyBy('page_name');

        // foreach ($newImages as $pageName => $newImage) {

        //     if (!isset($oldImages[$pageName])) {
        //         continue;
        //     }
        //     $oldImage = $oldImages[$pageName];

        //     $isChanged = false;

        //     $imageFile = $newImage['image_path'];
        //     $newName = $newImage['image_path']->getClientOriginalName();
        //     $oldName = $oldImage->image_path;

        //     if ($newName !== $oldName) { $isChanged = true; }

        //     if ($isChanged) {
        //         Storage::delete('products/'.$product->id.'/'.$oldName);
        //         $fileName = Str::slug($request->name).'_product_'.$pageName.'.'.$newImage['image_path']->getClientOriginalExtension();
        //         $imageFile->storeAs('products/'.$product->id.'/', $fileName);
        //         $oldImage->update([
        //             'page_name' => $newImage['page_name'],
        //             'image_path' => $fileName,
        //         ]);
        //     }
        // }

        // foreach ($request->input('images', []) as $index => $imageData) {
        //     $imageFile = $request->file("images.$index.image_path");
        //     $fileName = Str::slug($request->name).'_product_'.$index.'.'.$imageFile->getClientOriginalExtension();

        //     $imageFile->storeAs('products', $fileName);

        //     $product->productImages()->update([
        //         'page_name' => $imageData['page_name'],
        //         'image_path' => $fileName,
        //     ]);
        // }
    }

    public function delete(Product $product)
    {

        $product->translations()->delete();
        return Product::destroy($product->id);
    }
}
