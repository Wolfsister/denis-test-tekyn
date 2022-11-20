<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class SearchProductManager
{
    public const NAME_SEARCH_WORD = 'name';
    public const CODE_SEARCH_WORD = 'ean';
    public const BRAND_SEARCH_WORD = 'marque';

    private const AVAILABLE_SEARCH_WORDS = [
        self::NAME_SEARCH_WORD, self::CODE_SEARCH_WORD, self::BRAND_SEARCH_WORD
    ];

    public function getAllowedQueryParams(Request $request): array
    {
        $queryParams = [];
        foreach (self::AVAILABLE_SEARCH_WORDS as $availableSearchWord) {
            if ($request->get($availableSearchWord)) {
                $queryParams[$availableSearchWord] = $request->get($availableSearchWord);
            }
        }
        return $queryParams;
    }

}