<?php

namespace Tests\Application\Actions\Laureate;

use App\Application\Actions\ActionPayload;
use App\Infrastructure\Integration\NobelPrizeApi;
use DI\Container;
use Tests\TestCase;

class ListLaureatesActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $allLaureatesJsonMock = file_get_contents(__DIR__ . '/allLaureates.json');
        $expectedJsonDataResponse = file_get_contents(__DIR__ . '/expectedResponseData.json');

        $nobelPrizeApiProphecy = $this->prophesize(NobelPrizeApi::class);
        $nobelPrizeApiProphecy
            ->getAllLaureates()
            ->willReturn($allLaureatesJsonMock)
            ->shouldBeCalledOnce();

        $container->set(NobelPrizeApi::class, $nobelPrizeApiProphecy->reveal());

        $request = $this->createRequest('GET', '/laureates');
        $response = $app->handle($request);

        $responseBody = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, json_decode($expectedJsonDataResponse));
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $decodedPayload = json_decode($responseBody, true);

        $this->assertEquals($serializedPayload, $responseBody);
        $this->assertEquals(20, count($decodedPayload['data']));
    }
}
