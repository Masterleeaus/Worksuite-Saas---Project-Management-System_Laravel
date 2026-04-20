<?php

namespace Modules\AdminModule\Services;

class AdvanceSearch
{
    public function pageSearchList(string $keyword, string $type): array
    {
        return [];
    }

    public function searchMenuList(string $keyword, string $type): array
    {
        return [];
    }

    public function searchModelList(string $keyword, string $type, $user = null): array
    {
        return [];
    }

    public function sortByPriority(array $formattedRoutes, array $menuSearchResults, array $modelSearchResults, string $searchKeyword): array
    {
        return array_values(array_merge($formattedRoutes, $menuSearchResults, $modelSearchResults));
    }

    public function getSortRecentSearchByType(array $formattedResult): array
    {
        return $formattedResult;
    }
}
