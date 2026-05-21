<?php

namespace App\Repositories;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Shop;
use App\Repositories\Contracts\ShopInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ShopRepository implements ShopInterface
{
    public function index()
    {
        return Shop::query()->select(['id','name','email','address','city','state','country','phone'])->paginate(20);
    }

    public function myShops()
    {
        return Shop::query()->where('user_id', Auth::id())->select(['id','name','email','address','city','state','country','phone'])->paginate(20);
    }

    public function bannedShops()
    {
        return Shop::query()->onlyTrashed()->select(['id','name','email','address','city','state','country','phone'])->paginate(20);
    }

    public function search(Request $request)
    {
        return Shop::query()->where('name', 'like', '%' . $request->nameKey . '%')->select(['id','name','email','address','city','state','country','phone'])->paginate(20);
    }

    public function show(int $id)
    {
        return Shop::with('user')->findOrFail($id);
    }

    public function store(CreateShopRequest $request)
    {
        return Shop::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'user_id' => Auth::id(),
            'pincode' => $request->pincode,
            'website' => $request->website,
            'bank_name' => $request->bank_name,
            'bank_code' => $request->bank_code,
            'bank_country' => $request->bank_country,
            'bank_address' => $request->bank_address,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
        ]);
    }

    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $shop->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'pincode' => $request->pincode,
            'website' => $request->website,
            'bank_name' => $request->bank_name,
            'bank_code' => $request->bank_code,
            'bank_country' => $request->bank_country,
            'bank_address' => $request->bank_address,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
        ]);

        return $shop;
    }

    public function ban(Shop $shop)
    {
        Shop::destroy($shop->id);
        return $shop;
    }

    public function unban(Shop $shop)
    {
        $shop->restore();
        return $shop;
    }

    public function delete(Shop $shop)
    {
        return $shop->forceDelete();
    }
}
