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

to the require section of your `composer.json`

## Usage

Create two players with current ratings:

```php
use Youhey\Glicko2\Glicko2;
use Youhey\Glicko2\Match;
use Youhey\Glicko2\Player;

$glicko = new Glicko2();

$player = new Player(1700.0, 250.0, 0.05);
$opponent = new Player(1650.0, 350.0, 0.06);

$match = new Match($player, $opponent, 1.0, 0.0);
$glicko->calculateMatch($match);

$match = new Match($player, $opponent, 3.0, 2.0);
$glicko->calculateMatch($match);

$newPlayerRating = $player->getRating();
$newOpponentRating = $opponent->getRating();
```

## Author

[Ikeda Youhei](https://github.com/youhey/), e-mail: [youhey.ikeda@gmail.com](mailto:youhey.ikeda@gmail.com)

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)
