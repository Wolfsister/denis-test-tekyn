<?php

use App\Entity\User;
use App\Service\SubstitutionManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SubstitutionTest extends KernelTestCase
{
    private SubstitutionManager $substitutionService;
    private array $similarProduct;

    protected function setUp(): void
    {
        $this->substitutionService = static::getContainer()->get(SubstitutionManager::class);

        $this->similarProduct = [
            "brands" => "Cristaline",
            "categories_tags_fr" => [
                0 => "Boissons",
                1 => "Eaux",
                2 => "Eaux de sources",
            ],
            "code" => "3274080005003",
            "nutriscore_score" => 10,
            "product_name" => "Eau de source",
        ];
    }

    public function testCorrectSubstitutionAddedToUser(): void
    {
        $user = new User();
        $eanCodeToReplace = '8024884500403';
        $this->substitutionService->addSubstitution($user, $eanCodeToReplace, $this->similarProduct);

        self::assertCount(1, $user->getSubstitutions());
        self::assertSame($user->getSubstitutions()->first()->getEanCodeOfSubstitute(), $this->similarProduct['code']);
        self::assertSame($user->getSubstitutions()->first()->getEanCodeToReplace(), $eanCodeToReplace);
    }

    public function testSubstitutionAlreadySaved(): void
    {
        $user = new User();
        $eanCodeToReplace = '8024884500403';
        $this->substitutionService->addSubstitution($user, $eanCodeToReplace, $this->similarProduct);
        self::assertCount(1, $user->getSubstitutions());

        $this->substitutionService->addSubstitution($user, $eanCodeToReplace, $this->similarProduct);
        self::assertCount(1, $user->getSubstitutions());
    }
}