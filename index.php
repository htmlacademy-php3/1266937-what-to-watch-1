<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use App\Repositories\OmdbFilmRepository;
use App\Services\FilmService;
use Dotenv\Dotenv;

require_once './vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['OMDB_API_KEY'];

$httpFactory = new HttpFactory();
$httpClient = new Client();

$filmRepository = new OmdbFilmRepository(
  httpClient: $httpClient,
  requestFactory: $httpFactory,
  uriFactory: $httpFactory,
  apiKey: $apiKey
);

$service = new FilmService($filmRepository);

$film = $service->getFilm('tt3896198');

print_r($film);
