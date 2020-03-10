<?php

use Youhey\Glicko2\Glicko2;
use Youhey\Glicko2\Match;
use Youhey\Glicko2\Player;

require_once __DIR__ . '/../vendor/autoload.php';

define('ATTEMPT_LIMITS', 1000000);
define('DEFAULT_RATE', 1500.0);

$input = '';
while (($buffer = fgets(STDIN)) !== false) {
    $input .= trim($buffer);
}

if (!feof(STDIN)) {
    echo "Input Error\n";
    exit;
}

$players = [];

$json = json_decode($input, true, 512, JSON_THROW_ON_ERROR);

foreach ($json as $row) {
    $id = $row['id'] ?? null;
    $skill = $row['skill'] ?? 0;

    if ($id) {
        $players[] = compact('id', 'skill') + [
            'win' => 0,
            'draw' => 0,
            'loss' => 0,
            'rating' => new Player(),
        ];
    }
}

$rating = new Glicko2();

for ($i = 0; $i < ATTEMPT_LIMITS; $i++) {
    $player1 = &$players[random_int(0, (count($players) - 1))];
    $player2 = &$players[random_int(0, (count($players) - 1))];

    if ($player1['id'] !== $player2['id']) {
        $score1 = $player1['skill'] * (0.5 + (lcg_value() * abs(2.0 - 0.5)));
        $score2 = $player2['skill'] * (0.5 + (lcg_value() * abs(2.0 - 0.5)));

        $match = new Match($player1['rating'], $player2['rating'], $score1, $score2);
        switch ($match->getResult()) {
            case 1:
                $player1['win']++;
                $player2['loss']++;
                break;
            case 0:
                $player1['loss']++;
                $player2['win']++;
                break;
            case 0.5:
                $player1['draw']++;
                $player2['draw']++;
                break;
        }
        $rating->calculateMatch($match);
    }

    unset($player1, $player2);
}

foreach ($players as &$player) {
    $player['r'] = $player['rating']->getRating();
    $player['mu'] = $player['rating']->getRatingMu();
    $player['rd'] = $player['rating']->getRatingDeviation();
    $player['phi'] = $player['rating']->getRatingDeviationPhi();
    $player['sigma'] = $player['rating']->getRatingVolatility();
    unset($player['rating']);
}

usort($players, fn($a, $b) => ($a['r'] <=> $b['r']));

// echo json_encode($players, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE |JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);

$fp = new SplTempFileObject();

$fp->fputcsv(['id', 'skill', 'win', 'draw', 'loss', 'r', 'rd', 'mu', 'phi', 'sigma']);

foreach ($players as $player) {
    $fp->fputcsv([
        $player['id'],
        $player['skill'],
        $player['win'],
        $player['draw'],
        $player['loss'],
        $player['r'],
        $player['rd'],
        $player['mu'],
        $player['phi'],
        $player['sigma'],
    ]);
}

$fp->rewind();
while (!$fp->eof()) {
    echo $fp->fgets();
}
