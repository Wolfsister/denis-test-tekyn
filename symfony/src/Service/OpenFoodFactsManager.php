<?php

namespace App\Service;

class OpenFoodFactsManager
{

    // TODO: use that to populate favorite entity
    public function getSingleProductFromOpenFoodFactsWithEanCode(string $eanCode): array
    {
        $url = sprintf("https://fr.openfoodfacts.org/api/v2/search?action=process&code=%s&fields=code,product_name,nutriscore_score,categories_tags_fr,tags&sort_by=unique_scans_n?sort_by=nutriscore_score", $eanCode);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        switch ($response['count']) {
            case 0:
                throw new \NoOpenFoodFactsProductFoundException();
            case 1:
                break;
            default:
                throw new \Exception("More than one product was found.");
        }
        return $this->extractProductsFromOpenFoodFactsResponse($response)[0];
    }

    private function extractProductsFromOpenFoodFactsResponse(array $openFoodFactsReponse): array
    {
        return $openFoodFactsReponse['products'];
    }

    public function getProductByCategoriesTagsSortedByNutriscore(array $categoriesTags): array
    {
        $categoriesTagsAsString = urlencode(implode(",", $categoriesTags));
        $url = sprintf("https://fr.openfoodfacts.org/api/v2/search?action=process&categories_tags_fr=%s&fields=code,product_name,nutriscore_score,categories_tags_fr,tags,brands&sort_by=unique_scans_n?sort_by=nutriscore_score", $categoriesTagsAsString);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $response['products'][0] ?? [];
    }

    public function searchProducts(array $queryParams): array
    {
        $url = "https://fr.openfoodfacts.org/cgi/search.pl?action=process&json=1&page_size=20";

        if (isset($queryParams[SearchProductManager::CODE_SEARCH_WORD])) {
            $url .= sprintf("&code=%s", urlencode($queryParams[SearchProductManager::CODE_SEARCH_WORD]));
        }

        if (isset($queryParams[SearchProductManager::BRAND_SEARCH_WORD])) {
            $url .= sprintf("&brands=%s", urlencode($queryParams[SearchProductManager::BRAND_SEARCH_WORD]));
        }

        if (isset($queryParams[SearchProductManager::NAME_SEARCH_WORD])) {
            $url .= sprintf("&search_terms=%s", urlencode($queryParams[SearchProductManager::NAME_SEARCH_WORD]));
        }

        $url .= "&fields=code,product_name,nutriscore_score,categories_tags_fr,tags,brands,allergens,ingredients,nutrition_grade_fr&sort_by=unique_scans_n?sort_by=nutriscore_score";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response['products'] ?? [];
    }

}