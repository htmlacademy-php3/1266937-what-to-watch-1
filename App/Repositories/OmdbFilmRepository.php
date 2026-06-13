<?php

namespace App\Repositories;

use App\Repositories\Interfaces\FilmRepositoryInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class OmdbFilmRepository implements FilmRepositoryInterface
{
  private const string BASE_URL = 'http://omdbapi.com';
  public function __construct(
    private ClientInterface $httpClient,
    private RequestFactoryInterface $requestFactory,
    private UriFactoryInterface $uriFactory,
    private string $apiKey
  ) {
  }

  public function getFilmByImdbId(string $imdbId): ?array
  {
    $params = [
      'apikey' => $this->apiKey,
      'i' => $imdbId,
    ];

    $apiUri = $this->uriFactory->createUri(self::BASE_URL)->withQuery(http_build_query($params));

    $request = $this->requestFactory->createRequest('GET', $apiUri);

    $response = $this->httpClient->sendRequest($request);

    return json_decode($response->getBody()->getContents(), true);
  }
}
