<?php
namespace App\Service;

use App\Entity\Substitution;
use App\Entity\User;

class SubstitutionManager
{

    public function addSubstitution(User $user, string $eanCodeToReplace, string $eanCodeOfSubstitute): void
    {
        if (empty($eanCodeToReplace) || empty($eanCodeOfSubstitute)) {
            throw new \InvalidArgumentException("Please verify that your codes are not empty.");
        }

        if ($eanCodeToReplace === $eanCodeOfSubstitute) {
            throw new \InvalidArgumentException("Please choose two different codes.");
        }
        $substitution = (new Substitution())->setEanCodeToReplace($eanCodeToReplace)->setEanCodeOfSubstitute($eanCodeOfSubstitute);
        $user->addSubstitution($substitution);
    }
}