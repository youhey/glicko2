# Glicko2

A PHP implementation of [Glicko2 rating system](http://www.glicko.net/glicko.html)

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require youhey/glicko2 "~1.0.0"
```

or add

```
"youhey/glicko2": "~1.0.0"
```

to the require section of your ```composer.json```

## Usage

Create two players with current ratings:

```php
use Youhey\Glicko2\Glicko2;
use Youhey\Glicko2\Match;
use Youhey\Glicko2\MatchCollection;
use Youhey\Glicko2\Player;

$glicko = new Glicko2();

$player1 = new Player(1700, 250, 0.05);
$player2 = new Player();

$match = new Match($player1, $player2, 1, 0);
$glicko->calculateMatch($match);

$match = new Match($player1, $player2, 3, 2);
$glicko->calculateMatch($match);

// or

$matchCollection = new MatchCollection();
$matchCollection->addMatch(new Match($player1, $player2, 1, 0));
$matchCollection->addMatch(new Match($player1, $player2, 3, 2));
$glicko->calculateMatches($matchCollection);

$newPlayer1R = $player1->getR();
$newPlayer2R = $player2->getR();
```

## Author

[Ikeda Youhei](https://github.com/youhey/), e-mail: [youhey.ikeda@gmail.com](mailto:youhey.ikeda@gmail.com)

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)
