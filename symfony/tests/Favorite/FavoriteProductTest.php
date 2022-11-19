<?php

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FavoriteProductTest extends KernelTestCase
{

    public function testCorrectEanAddedToUsersFavorites(): void
    {
        $user = new User();
        $eanCode = '8024884500403';
        $this->favoriteProductService->addFavoriteProduct($user, $eanCode);

        self::assertCount(1, $user->getFavoriteProducts());
        self::assertSame($user->getFavoriteProducts()->toArray()[0]->getCode(), $eanCode);
    }

    public function testAddNullEan(): void
    {
        $user = new User();
        $eanCode = null;
        self::expectException();
        $this->favoriteProductService->addFavoriteProduct($user, $eanCode);
    }

    public function testAddEmptyEan(): void
    {
        $user = new User();
        $eanCode = null;
        self::expectExceptionMessage('An empty EAN was given.');
        $this->favoriteProductService->addFavoriteProduct($user, $eanCode);
    }

}