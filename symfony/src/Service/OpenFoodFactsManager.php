<?php

namespace App\Service;

class OpenFoodFactsManager
{

    // TODO: use that to populate favorite entity
    public function getSingleProductFromOpenFoodFactsWithEanCode(string $eanCode): array
    {
        //        $url = "https://fr.openfoodfacts.org/api/v2/search?action=process&search_terms=biscino&fields=code,product_name,nutriscore_score&sort_by=unique_scans_n&page_size=24?sort_by=nutriscore_score";
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

}