<?php

use App\Entity\User;
use App\Service\SubstitutionManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SubstitutionTest extends KernelTestCase
{
    private SubstitutionManager $substitutionService;

    protected function setUp(): void
    {
        $this->substitutionService = static::getContainer()->get(SubstitutionManager::class);
    }

    public function testCorrectSubstitutionAddedToUser(): void
    {
        $user = new User();
        $eanCodeToReplace = '8024884500403';
        $eanCodeOfSubstitute = '11111111';
        $this->substitutionService->addSubstitution($user, $eanCodeToReplace, $eanCodeOfSubstitute);

        self::assertCount(1, $user->getSubstitutions());
        self::assertSame($user->getSubstitutions()->first()->getEanCodeOfSubstitute(), $eanCodeOfSubstitute);
        self::assertSame($user->getSubstitutions()->first()->getEanCodeToReplace(), $eanCodeToReplace);
    }

    public function testSubstitutionSameAsReplaced(): void
    {
        $user = new User();
        $eanCodeToReplace = $eanCodeOfSubstitute = '8024884500403';
        self::expectExceptionMessage("Please choose two different codes.");
        $this->substitutionService->addSubstitution($user, $eanCodeToReplace, $eanCodeOfSubstitute);
    }

    public function testSubstitutionAlreadySaved(): void
    {
        $user = new User();
        $eanCodeToReplace = '8024884500403';
        $eanCodeOfSubstitute = '11111111';
        $eanCodeOfSecondSubstitute = '2222222';
        $this->substitutionService->addSubstitution($user, $eanCodeToReplace, $eanCodeOfSubstitute);
        self::assertCount(1, $user->getSubstitutions());

        $this->substitutionService->addSubstitution($user, $eanCodeToReplace, $eanCodeOfSubstitute);
        self::assertCount(1, $user->getSubstitutions());

        $this->substitutionService->addSubstitution($user, $eanCodeToReplace, $eanCodeOfSecondSubstitute);
        self::assertCount(2, $user->getSubstitutions());
    }

    public function testEmptySubstitutionParam(): void
    {
        $user = new User();

        self::expectExceptionMessage("Please verify that your codes are not empty.");
        $this->substitutionService->addSubstitution($user, '', '11111');

        self::expectExceptionMessage("Please verify that your codes are not empty.");
        $this->substitutionService->addSubstitution($user, '11111', '');

        self::expectExceptionMessage("Please verify that your codes are not empty.");
        $this->substitutionService->addSubstitution($user, '', '');
    }


}