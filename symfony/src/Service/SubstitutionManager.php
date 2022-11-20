<?php
namespace App\Service;

use App\Entity\Substitution;
use App\Entity\User;

class SubstitutionManager
{
    public function __construct(private OpenFoodFactsManager $openFoodFactsManager)
    {
    }

    public function addSubstitution(User $user, string $eanCodeToReplace, array $similarProduct): void
    {
        $substitution = (new Substitution())
            ->setEanCodeToReplace($eanCodeToReplace)
            ->setEanCodeOfSubstitute($similarProduct['code'])
            ->setBrands($similarProduct['brands'])
            ->setCategories($similarProduct['categories_tags_fr'])
            ->setNutriscore($similarProduct['nutriscore_score'])
            ->setProductName($similarProduct['product_name']);
        $user->addSubstitution($substitution);
    }

    public function searchSimilarProduct(string $eanCodeToReplace): array
    {
        $product = $this->openFoodFactsManager->getSingleProductFromOpenFoodFactsWithEanCode($eanCodeToReplace);

        $categoriesTags = $product['categories_tags_fr'];
        $similarProduct = $this->openFoodFactsManager->getProductByCategoriesTagsSortedByNutriscore($categoriesTags);

        return $similarProduct;
    }

    public function getSubstituteInfos(User $user, string $eanCodeToReplace): ?array {
        foreach ($user->getSubstitutions() as $substitution) {
            if ($substitution->getEanCodeToReplace() === $eanCodeToReplace) {
                return [
                    'code' => $substitution->getEanCodeOfSubstitute(),
                    'nutriscore' => $substitution->getNutriscore(),
                    'name' => $substitution->getProductName(),
                    'brands' => $substitution->getBrands(),
                    'categories' => $substitution->getCategories()
                ];
            }
        }
        return null;
    }
}