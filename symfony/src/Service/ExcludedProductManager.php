<?php
namespace App\Service;

use App\Entity\ExcludedProduct;
use App\Entity\User;

class ExcludedProductManager
{
    public function addExcludedProduct(User $user, string $code): void
    {
        if (empty($code)) {
            throw new \InvalidArgumentException("An empty EAN was given.");
        }
        $excludedProduct = (new ExcludedProduct())->setCode($code);
        $user->addExcludedProduct($excludedProduct);
    }

}