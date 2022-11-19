<?php
namespace App\Service;

use App\Entity\FavoriteProduct;
use App\Entity\User;
use App\Repository\FavoriteProductRepository;

class FavoriteProductManager
{
    public function __construct(private FavoriteProductRepository $favoriteProductRepository)
    {
    }

    public function addFavoriteProduct(User $user, string $code): void
    {
        if (empty($code)) {
            throw new \InvalidArgumentException("An empty EAN was given.");
        }
        $favoriteProduct = (new FavoriteProduct())->setCode($code);
        $user->addFavoriteProduct($favoriteProduct);
    }

    public function removeFavoriteProduct(User $user, string $code): void
    {
        if (empty($code)) {
            throw new \InvalidArgumentException("An empty EAN was given.");
        }

        $favoriteProductToRemove = $this->favoriteProductRepository->findExistingFavoriteProductOfUser($user, $code);
        $user->removeFavoriteProduct($favoriteProductToRemove);
    }

}