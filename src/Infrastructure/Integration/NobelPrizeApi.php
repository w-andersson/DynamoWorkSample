<?php

namespace App\Infrastructure\Integration;

class NobelPrizeApi
{
    public function getAllLaureates(): string
    {
        // Fetch 1 laureate in order to get total count of all laureates.
        $firstResponseJson = $this->fetchLaureatesFromApi(1);

        $decodedFirstResponse = json_decode($firstResponseJson, true);

        $totalLaureatesCount = $decodedFirstResponse['meta']['count'];

        return $this->fetchLaureatesFromApi($totalLaureatesCount);
    }

    private function fetchLaureatesFromApi(int $limit): string
    {
        return file_get_contents('https://api.nobelprize.org/2.1/laureates?limit=' . $limit);
    }
}
