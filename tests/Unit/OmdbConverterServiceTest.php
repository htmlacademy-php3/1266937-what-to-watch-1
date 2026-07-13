<?php

use App\Services\OmdbConverterService;

it('converts omdb data into database format', function () {
    $service = new OmdbConverterService();

    $omdbData = [
        'Title' => 'The Matrix',
        'Poster' => 'matrix_poster.jpg',
        'Plot' => 'A computer hacker learns from mysterious rebels about the true nature of his reality.',
        'Runtime' => '136 min',
        'Year' => '31 Mar 1999',
        'Genre' => 'Action, Sci-Fi',
        'Actors' => 'Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss',
        'Director' => 'Lana Wachowski, Lilly Wachowski',
    ];

    $result = $service->convert($omdbData);

    $this->assertEquals('The Matrix', $result['name']);
    $this->assertEquals('matrix_poster.jpg', $result['poster_image']);
    $this->assertEquals(136, $result['run_time']);
    $this->assertEquals(1999, $result['released']);
    $this->assertEquals(['Action', 'Sci-Fi'], $result['genres']);
    $this->assertEquals(['Keanu Reeves', 'Laurence Fishburne', 'Carrie-Anne Moss'], $result['actors']);
    $this->assertEquals(['Lana Wachowski', 'Lilly Wachowski'], $result['directors']);
});

it('converts n/a strings into null values', function () {
    $service = new OmdbConverterService();

    $omdbData = [
        'Title' => 'Unknown Film',
        'Poster' => 'N/A',
        'Plot' => 'N/A',
        'Runtime' => 'N/A',
        'Year' => 'N/A',
        'Genre' => 'N/A',
        'Actors' => 'N/A',
        'Director' => 'N/A',
    ];

    $result = $service->convert($omdbData);

    $this->assertNull($result['poster_image']);
    $this->assertNull($result['description']);
    $this->assertNull($result['run_time']);
    $this->assertNull($result['released']);
    $this->assertEquals([], $result['genres']);
    $this->assertEquals([], $result['actors']);
    $this->assertEquals([], $result['directors']);
});

it('handles empty array keys safely', function () {
    $service = new OmdbConverterService();

    $result = $service->convert([]);

    $this->assertNull($result['name']);
    $this->assertNull($result['run_time']);
    $this->assertNull($result['released']);
    $this->assertEquals([], $result['genres']);
});

