<?php

namespace Tests;

use Mockery;
use GuzzleHttp\ClientInterface;
use Andonovn\LaravelBetsApi\BetsApi;
use Andonovn\LaravelBetsApi\Exceptions\MissingConfigException;

class BetsApiTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    function it_can_be_instantiated()
    {
        $http = Mockery::mock(ClientInterface::class);
        $config = require __DIR__ . '/../config/bets_api.php';

        $betsApi = new BetsApi($http, $config);

        $this->assertInstanceOf(BetsApi::class, $betsApi);
    }

    /** @test */
    public function it_can_not_be_instantiated_without_config()
    {
        $http = Mockery::mock(ClientInterface::class);

        $this->expectException(MissingConfigException::class);

        new BetsApi($http, []);
    }

    /** @test */
    public function it_can_merge_paginated_responses()
    {
        $betsApi = Mockery::mock(BetsApi::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $leagues = [];

        for ($i = 0; $i < 10; $i++) {
            $betsApi->shouldReceive('leaguesCall')->once()->andReturn([
                'pager' => [
                    'total' => 100,
                    'per_page' => 10,
                ],
                'results' => [$i],
            ]);

            $leagues[] = $i;
        }

        $betsApiLeagues = $betsApi->soccerLeagues();

        $this->assertEquals($leagues, $betsApiLeagues);
    }
}
