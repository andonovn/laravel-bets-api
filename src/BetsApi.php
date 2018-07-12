<?php

namespace Andonovn\LaravelBetsApi;

use GuzzleHttp\{
    Client, ClientInterface
};
use Andonovn\LaravelBetsApi\Exceptions\{
    CallFailedException, InvalidConfigException, MissingConfigException
};

class BetsApi
{
    /**
     * @var Client
     */
    protected $http;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string|null
     */
    protected $country;

    /**
     * @var string|null
     */
    protected $date;

    /**
     * Fetcher constructor.
     *
     * @param  ClientInterface  $http
     * @param  array  $config
     * @throws MissingConfigException
     * @throws InvalidConfigException
     */
    public function __construct(ClientInterface $http, array $config)
    {
        $this->validateConfig($config);

        $this->http = $http;
        $this->config = $config;
    }

    /**
     * Validate the config data
     *
     * @param  array  $config
     * @throws MissingConfigException
     * @throws InvalidConfigException
     */
    protected function validateConfig(array $config)
    {
        $requiredKeys = ['token', 'endpoint'];

        foreach ($requiredKeys as $key) {
            if (array_key_exists($key, $config)) {
                if (is_string($key)) {
                    continue;
                }

                throw new InvalidConfigException('The following config option must be string: ' . $key);
            }

            throw new MissingConfigException('The following config option is missing: ' . $key);
        }
    }

    /**
     * Set the country
     *
     * @param  string  $country
     * @return BetsApi
     */
    public function forCountry(string $country) : BetsApi
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Set the date
     *
     * @param  string  $date
     * @return BetsApi
     */
    public function forDate(string $date) : BetsApi
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the given sport's leagues
     *
     * @param  int $sportId
     * @return array
     * @throws CallFailedException
     */
    public function leagues(int $sportId) : array
    {
        $leagues = [];

        $page = 1;

        do {
            $leagueResponse = $this->leaguesCall($sportId, $page++);

            $totalPages = (int) ceil(
                $leagueResponse['pager']['total'] / $leagueResponse['pager']['per_page']
            );

            $leagues = array_merge($leagues, $leagueResponse['results']);
        } while ($page <= $totalPages);

        return $leagues;
    }

    /**
     * Get the soccer's leagues
     *
     * @return array
     * @throws CallFailedException
     */
    public function soccerLeagues() : array
    {
        return $this->leagues(Glossary::SPORT_SOCCER);
    }

    /**
     * Get the basketball's leagues
     *
     * @return array
     * @throws CallFailedException
     */
    public function basketballLeagues() : array
    {
        return $this->leagues(Glossary::SPORT_BASKETBALL);
    }

    /**
     * Get the tennis's leagues
     *
     * @return array
     * @throws CallFailedException
     */
    public function tennisLeagues() : array
    {
        return $this->leagues(Glossary::SPORT_TENNIS);
    }

    /**
     * Get the cricket's leagues
     *
     * @return array
     * @throws CallFailedException
     */
    public function cricketLeagues() : array
    {
        return $this->leagues(Glossary::SPORT_CRICKET);
    }

    /**
     * Get the hockey's leagues
     *
     * @return array
     * @throws CallFailedException
     */
    public function hockeyLeagues() : array
    {
        return $this->leagues(Glossary::SPORT_ICE_HOCKEY);
    }

    /**
     * Get the baseball's leagues
     *
     * @return array
     * @throws CallFailedException
     */
    public function baseballLeagues() : array
    {
        return $this->leagues(Glossary::SPORT_BASEBALL);
    }

    /**
     * Get the American football's leagues
     *
     * @return array
     * @throws CallFailedException
     */
    public function americanFootballLeagues() : array
    {
        return $this->leagues(Glossary::SPORT_AMERICAN_FOOTBALL);
    }

    /**
     * Get the fight's leagues
     *
     * @return array
     * @throws CallFailedException
     */
    public function fightLeagues() : array
    {
        return $this->leagues(Glossary::SPORT_BOXING_UFC);
    }

    /**
     * Get the given league's events
     *
     * @param  int $sportId
     * @param  int $leagueId
     * @return array
     * @throws CallFailedException
     */
    public function events(int $sportId, int $leagueId) : array
    {
        $events = [];

        $page = 1;

        do {
            $eventsResponse = $this->eventsCall($sportId, $leagueId, $page++);

            $totalPages = (int) ceil(
                $eventsResponse['pager']['total'] / $eventsResponse['pager']['per_page']
            );

            $events = array_merge($events, $eventsResponse['results']);
        } while ($page <= $totalPages);

        return $events;
    }

    /**
     * Get the given sport's ended events
     *
     * @param  int $sportId
     * @param  int $leagueId
     * @return array
     * @throws CallFailedException
     */
    public function endedEvents(int $sportId, int $leagueId) : array
    {
        $events = [];

        $page = 1;

        do {
            $eventsResponse = $this->endedEventsCall($sportId, $leagueId, $page++);

            $totalPages = (int) ceil(
                $eventsResponse['pager']['total'] / $eventsResponse['pager']['per_page']
            );

            $events = array_merge($events, $eventsResponse['results']);
        } while ($page <= $totalPages);

        return $events;
    }

    /**
     * Get the given event's odds
     *
     * @param  int $eventId
     * @return array
     * @throws CallFailedException
     */
    public function odds(int $eventId) : array
    {
        $odds = [];

        $oddsResponse = $this->oddsCall($eventId);

        foreach ($oddsResponse['results'] as $bookmaker => $bookmakerOdds) {
            if (
                ! isset($bookmakerOdds['end']['1_1']['home_od'])
                || ! isset($bookmakerOdds['end']['1_1']['draw_od'])
                || ! isset($bookmakerOdds['end']['1_1']['away_od'])
            ) {
                continue;
            }

            $odds['result'] = [
                'home' => $bookmakerOdds['end']['1_1']['home_od'],
                'draw' => $bookmakerOdds['end']['1_1']['draw_od'],
                'away' => $bookmakerOdds['end']['1_1']['away_od'],
            ];

            break;
        }

        if (empty($odds['result'])) {
            $odds['result'] = [
                'home' => null,
                'draw' => null,
                'away' => null,
            ];
        }

        return $odds;
    }

    /**
     * Build the requested route's endpoint
     *
     * @param  string  $route
     * @param  int|null  $page
     * @return string
     */
    protected function endpoint(string $route, ?int $page = null) : string
    {
        $endpoint = $this->config['endpoint']
            . $route
            . '?token=' . $this->config['token'];

        if ($page) {
            $endpoint .= '&page=' . $page;
        }

        return $endpoint;
    }

    /**
     * Call the BetsApi to get the leagues
     *
     * @param  int $sportId
     * @param  int $page
     * @return array
     * @throws CallFailedException
     */
    protected function leaguesCall(int $sportId, int $page) : array
    {
        $endpoint = $this->endpoint('league', $page) . '&sport_id=' . $sportId;

        $endpoint .= $this->country ? '&cc=' . $this->country : '';

        $response = $this->http->get($endpoint);

        if ($response->getStatusCode() !== 200) {
            throw new CallFailedException(
                'Status: ' . $response->getStatusCode() . '. Content: ' . json_encode($response->getBody()->getContents())
            );
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Call the BetsApi to get the events
     *
     * @param  int $sportId
     * @param  int $leagueId
     * @param  int $page
     * @return array
     * @throws CallFailedException
     */
    protected function eventsCall(int $sportId, int $leagueId, int $page) : array
    {
        $response = $this->http->get(
            $this->endpoint('events/upcoming', $page) . '&league_id=' . $leagueId . '&sport_id=' . $sportId
        );

        if ($response->getStatusCode() !== 200) {
            throw new CallFailedException(
                'Status: ' . $response->getStatusCode() . '. Content: ' . json_encode($response->getBody()->getContents())
            );
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Call the BetsApi to get the ended events
     *
     * @param  int $sportId
     * @param  int $leagueId
     * @param  int $page
     * @return array
     * @throws CallFailedException
     */
    protected function endedEventsCall(int $sportId, int $leagueId, int $page) : array
    {
        $endpoint = $this->endpoint('events/ended', $page)
            . '&sport_id=' . $sportId . '&league_id=' . $leagueId;
        $endpoint .= $this->date ? '&day=' . $this->date : '';

        $response = $this->http->get($endpoint);

        if ($response->getStatusCode() !== 200) {
            throw new CallFailedException(
                'Status: ' . $response->getStatusCode() . '. Content: ' . json_encode($response->getBody()->getContents())
            );
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Call the BetsApi to get the odds
     *
     * @param  int $eventId
     * @return array
     * @throws CallFailedException
     */
    protected function oddsCall(int $eventId) : array
    {
        $response = $this->http->get(
            $this->endpoint('event/odds/summary') . '&event_id=' . $eventId
        );

        if ($response->getStatusCode() !== 200) {
            throw new CallFailedException(
                'Status: ' . $response->getStatusCode() . '. Content: ' . json_encode($response->getBody()->getContents())
            );
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}