<?php

use App\Entity\User;
use App\Service\FavoriteProductService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FavoriteProductTest extends KernelTestCase
{
    private FavoriteProductService $favoriteProductService;

    protected function setUp(): void
    {
        $this->favoriteProductService = static::getContainer()->get(FavoriteProductService::class);
    }

    public function testCorrectEanAddedToUsersFavorites(): void
    {
        $user = new User();
        $eanCode = '8024884500403';
        $this->favoriteProductService->addFavoriteProduct($user, $eanCode);

        self::assertCount(1, $user->getFavoriteProducts());
        self::assertSame($user->getFavoriteProducts()->toArray()[0]->getCode(), $eanCode);
    }

    public function testAddAlreadySavedEan(): void
    {
        $user = new User();
        $eanCode = '8024884500403';
        $this->favoriteProductService->addFavoriteProduct($user, $eanCode);
        $this->favoriteProductService->addFavoriteProduct($user, $eanCode);

        self::assertCount(1, $user->getFavoriteProducts());
        self::assertSame($user->getFavoriteProducts()->toArray()[0]->getCode(), $eanCode);
    }

    public function testAddNullEan(): void
    {
        $user = new User();
        $eanCode = null;
        self::expectError();
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