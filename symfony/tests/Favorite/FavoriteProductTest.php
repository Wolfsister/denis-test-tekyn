<?php

use App\Entity\User;
use App\Service\FavoriteProductManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FavoriteProductTest extends KernelTestCase
{
    private FavoriteProductManager $favoriteProductManager;

    protected function setUp(): void
    {
        $this->favoriteProductManager = static::getContainer()->get(FavoriteProductManager::class);
    }

    public function testCorrectEanAddedToUsersFavorites(): void
    {
        $user = new User();
        $eanCode = '8024884500403';
        $this->favoriteProductManager->addFavoriteProduct($user, $eanCode);

        self::assertCount(1, $user->getFavoriteProducts());
        self::assertSame($user->getFavoriteProducts()->toArray()[0]->getCode(), $eanCode);
    }

    public function testAddAlreadySavedEan(): void
    {
        $user = new User();
        $eanCode = '8024884500403';
        $this->favoriteProductManager->addFavoriteProduct($user, $eanCode);
        $this->favoriteProductManager->addFavoriteProduct($user, $eanCode);

        self::assertCount(1, $user->getFavoriteProducts());
        self::assertSame($user->getFavoriteProducts()->toArray()[0]->getCode(), $eanCode);
    }

    public function testAddNullEan(): void
    {
        $user = new User();
        $eanCode = null;
        self::expectError();
        $this->favoriteProductManager->addFavoriteProduct($user, $eanCode);
    }

    public function testAddEmptyEan(): void
    {
        $user = new User();
        $eanCode = '';
        self::expectExceptionMessage('An empty EAN was given.');
        $this->favoriteProductManager->addFavoriteProduct($user, $eanCode);
    }

}