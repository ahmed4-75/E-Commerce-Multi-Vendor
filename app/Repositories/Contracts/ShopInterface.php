<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Shop;

interface ShopInterface
{
    public function index();
    public function show(int $id);
    public function store(CreateShopRequest $request);
    public function update(UpdateShopRequest $request, Shop $shop);
    public function delete(Shop $shop);
}