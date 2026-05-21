<?php

namespace App\Services;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Shop;
use App\Repositories\Contracts\ShopInterface;
use Illuminate\Http\Request;

class ShopService
{
    public function __construct(
        protected ShopInterface $shopRepository
    ) {}

    public function index()
    {
        return $this->shopRepository->index();
    }

    public function myShops()
    {
        return $this->shopRepository->myShops();
    }

    public function bannedShops()
    {
        return $this->shopRepository->bannedShops();
    }

    public function search(Request $request)
    {
        return $this->shopRepository->search($request);
    }

    public function show(int $id)
    {
        return $this->shopRepository->show($id);
    }

    public function store(CreateShopRequest $request)
    {
        return $this->shopRepository->store($request);
    }

    public function update(UpdateShopRequest $request, int $id)
    {
        $shop = Shop::findOrFail($id);
        return $this->shopRepository->update($request, $shop);
    }

    public function ban(int $id)
    {
        $shop = Shop::findOrFail($id);
        return $this->shopRepository->ban($shop);
    }

    public function unban(int $id)
    {
        $shop = Shop::findOrFail($id);
        return $this->shopRepository->unban($shop);
    }

    public function delete(int $id)
    {
        $shop = Shop::findOrFail($id);

        if ($shop->products()->withTrashed()->exists()) {
            throw new \Exception(
                'Can not delete the shop because it has related products. Move the products or delete them.'
            );
        }

        return $this->shopRepository->delete($shop);
    }
}
