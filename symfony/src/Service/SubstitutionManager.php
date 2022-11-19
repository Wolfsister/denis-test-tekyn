<?php
namespace App\Service;

use App\Entity\Substitution;
use App\Entity\User;

class SubstitutionManager
{

    public function addSubstitution(User $user, string $eanCodeToReplace, string $eanCodeOfSubstitute): void
    {
        $substitution = (new Substitution())->setEanCodeToReplace($eanCodeToReplace)->setEanCodeOfSubstitute($eanCodeOfSubstitute);
        $user->addSubstitution($substitution);
    }
}