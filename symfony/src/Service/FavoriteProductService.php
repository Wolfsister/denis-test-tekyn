<?php
namespace App\Service;

use App\Entity\FavoriteProduct;
use App\Entity\User;

class FavoriteProductService
{

    public function addFavoriteProduct(User $user, string $code): void
    {
        $favoriteProduct = (new FavoriteProduct())->setCode($code);
        $user->addFavoriteProduct($favoriteProduct);
    }

}