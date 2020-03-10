<?php

define('NUMBER_OF_PLAYERS', 100);

$players = [];
for ($i = 0; $i < NUMBER_OF_PLAYERS; $i++) {
    $player = [
        'id' => sprintf('player_%08d', ($i + 1)),
        'skill' => random_int(1, 100),
    ];
    $players[$i] = $player;
}
echo json_encode($players, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE |JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
