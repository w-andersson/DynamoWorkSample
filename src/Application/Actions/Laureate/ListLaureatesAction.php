<?php

declare(strict_types=1);

namespace App\Application\Actions\Laureate;

use App\Application\Actions\Action;
use App\Infrastructure\Integration\NobelPrizeApi;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListLaureatesAction extends Action
{
    private NobelPrizeApi $nobelPrizeApi;

    public function __construct(LoggerInterface $logger, NobelPrizeApi $nobelPrizeApi)
    {
        parent::__construct($logger);

        $this->nobelPrizeApi = $nobelPrizeApi;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        /**
         * Considerations:
         *
         * Can't sort laureates by awarded date through the Nobel Prize API. Fetching all.
         * Might be more efficient to loop through each year.
         * Looping through each year might cause more overhead from multiple HTTP requests
         *
         * Full name, Birth date and Native country does not seem to have a value for all the laureates.
         *
         * Currently assuming 1 Nobel Prize per laureate. 4 people have multiple.
         *
         * No error handling. Assuming happy case.
         */

        $laureatesJson = $this->nobelPrizeApi->getAllLaureates();

        $decodedLaureatesJson = json_decode($laureatesJson, true);

        $laureates = [];

        foreach ($decodedLaureatesJson['laureates'] as $laureate) {
            $laureates[] = [
                'Full name' => $laureate['fullName']['en'] ?? '',
                'Birth date' => $laureate['birth']['date'] ?? '',
                'Native country' => $laureate['birth']['place']['country']['en'] ?? '',
                // Assuming 1 Nobel Prize, 4 people has received multiple
                'Category' => $laureate['nobelPrizes'][0]['category']['en'] ?? '',
                // Assuming 1 Nobel Prize, 4 people has received multiple
                'Date awarded' => $laureate['nobelPrizes'][0]['dateAwarded'] ?? '',
            ];
        }

        usort($laureates, function ($a, $b) {
            return $b['Date awarded'] <=> $a['Date awarded'];
        });

        return $this->respondWithData(array_splice($laureates, 0, 20));
    }
}
