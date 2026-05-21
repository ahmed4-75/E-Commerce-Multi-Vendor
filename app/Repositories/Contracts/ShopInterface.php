<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Shop;
use Illuminate\Http\Request;

interface ShopInterface
{
    public function index();
    public function myShops();
    public function bannedShops();
    public function search(Request $request);
    public function show(int $id);
    public function store(CreateShopRequest $request);
    public function update(UpdateShopRequest $request, Shop $shop);
    public function ban(Shop $shop);
    public function unban(Shop $shop);
    public function delete(Shop $shop);
}
