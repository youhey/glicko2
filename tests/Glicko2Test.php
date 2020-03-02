<?php

namespace Youhey\Glicko2\Test;

use PHPUnit\Framework\TestCase;
use Youhey\Glicko2\Glicko2;
use Youhey\Glicko2\Match;
use Youhey\Glicko2\Player;

class Glicko2Test extends TestCase
{
    /** @var Glicko2 */
    private Glicko2 $ratingSystem;

    public function setUp(): void
    {
        $this->ratingSystem = new Glicko2();

        parent::setUp();
    }

    public function testDefaultPlayer()
    {
        $player = new Player();

        $this->assertEquals(Player::DEFAULT_RATING, $player->getRating());
        $this->assertEquals(Player::DEFAULT_RATING_DEVIATION, $player->getRatingDeviation());
        $this->assertEquals(Player::DEFAULT_RATING_VOLATILITY, $player->getRatingVolatility());
    }

    public function testCustomPlayer(): void
    {
        $r = 1700.0;
        $rd = 300.0;
        $sigma = 0.04;

        $player = new Player($r, $rd, $sigma);

        $this->assertEquals($r, $player->getRating());
        $this->assertEquals($rd, $player->getRatingDeviation());
        $this->assertEquals($sigma, $player->getRatingVolatility());
    }

    public function testCalculateMatch(): void
    {
        $player1 = new Player(1500.0, 200.0, 0.06);
        $player2 = new Player(1400.0, 30.0, 0.06);

        $match = new Match($player1, $player2, 1.0, 0.0);
        $this->ratingSystem->calculateMatch($match);

        $this->assertEquals(1563.564, $this->round($player1->getRating()));
        $this->assertEquals(175.403, $this->round($player1->getRatingDeviation()));
        $this->assertEquals(0.06, $this->round($player1->getRatingVolatility()));

        $this->assertEquals(1398.144, $this->round($player2->getRating()));
        $this->assertEquals(31.67, $this->round($player2->getRatingDeviation()));
        $this->assertEquals(0.06, $this->round($player2->getRatingVolatility()));
    }

    /**
     * Example calculation:
     * Suppose a player rated 1500 competes against players rated 1400, 1550 and 1700, winning
     * the first game and losing the next two. Assume the 1500-rated player’s rating deviation
     * is 200, and his opponents’ are 30, 100 and 300, respectively. Assume the 1500 player has
     * volatility σ = 0.06, and the system constant τ is 0.5.
     *
     * @see http://www.glicko.net/glicko/glicko2.pdf
     */
    public function testExampleCalculation(): void
    {
        $player = new Player(1500.0, 200, 0.06);

        $player1 = new Player(1400.0, 30.0);
        $player2 = new Player(1550.0, 100.0);
        $player3 = new Player(1700.0, 300.0);

        $this->ratingSystem->calculateMatch(new Match($player, $player1, 1.0, 0.0));
        $this->ratingSystem->calculateMatch(new Match($player, $player2, 0.0, 1.0));
        $this->ratingSystem->calculateMatch(new Match($player, $player3, 0.0, 1.0));

        // Expected values
        // $this->assertEquals(1464.06, $this->round($player->getRating()));
        // $this->assertEquals(151.52, $player1->getRatingDeviation());
        // $this->assertEquals(0.05999, $player1->getRatingVolatility());

        // small error
        $this->assertEquals(1463.789, $this->round($player->getRating()));
        $this->assertEquals(151.873, $this->round($player->getRatingDeviation()));
        $this->assertEquals(0.060, $this->round($player->getRatingVolatility()));
    }

    /**
     * For different platforms compatibility
     *
     * @param float $value
     *
     * @return float
     */
    private function round(float $value): float
    {
        return round($value, 3);
    }
}
