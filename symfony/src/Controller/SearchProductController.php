<?php

namespace App\Controller;

use App\Service\FavoriteProductManager;
use App\Service\OpenFoodFactsManager;
use App\Service\SearchProductManager;
use App\Service\SubstitutionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchProductController extends AbstractController
{

    /**
     * @Route(name="search-product", path="/search", methods={"GET"})
     */
    public function searchProduct(Request $request, SearchProductManager $searchProductManager, FavoriteProductManager $favoriteProductManager, OpenFoodFactsManager $openFoodFactsManager, EntityManagerInterface $entityManager, SubstitutionManager $substitutionManager): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new \Exception("User not found.");
        }

        $allowedQueryParams = $searchProductManager->getAllowedQueryParams($request);

        if (count($allowedQueryParams) === 0) {
            throw new \InvalidArgumentException('You must at least have one parameter among name, ean and marque.');
        }

        $favoritesEans = $favoriteProductManager->searchInSavedFavoriteProducts($currentUser, $allowedQueryParams);

        $productsFromOpenFoodFacts = $openFoodFactsManager->searchProducts($allowedQueryParams);

        $favoriteProductKey = null;
        $normalizedProducts = [];
        foreach ($productsFromOpenFoodFacts as $key => $productFromOpenFoodFact) {
            if (!is_null($favoriteProductKey) && in_array($productFromOpenFoodFact['code'], $favoritesEans)) {
                $favoriteProductKey = $key;
            }
            $substituteInfos = $substitutionManager->getSubstituteInfos($currentUser, $productFromOpenFoodFact['code']);
            $normalizedProducts[$key] = $productFromOpenFoodFact;
            if ($substituteInfos) {
                $normalizedProducts[$key]['substitute'] = $substituteInfos;
            }
        }

        if ($favoriteProductKey) {
            $favoriteProduct = $normalizedProducts[$favoriteProductKey];
            unset($normalizedProducts[$favoriteProductKey]);
            array_unshift($normalizedProducts, $favoriteProduct);
        }

        return new JsonResponse($normalizedProducts, Response::HTTP_OK);
    }


}