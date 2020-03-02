<?php

declare(strict_types=1);

namespace Youhey\Glicko2;

/**
 * Match result
 */
class Match
{
    /** @var float The actual score for win */
    private const RESULT_WIN = 1.0;

    /** @var float The actual score for draw */
    private const RESULT_DRAW = 0.5;

    /** @var float The actual score for loss */
    private const RESULT_LOSS = 0.0;

    /** @var Player The player rating */
    private Player $player;

    /** @var Player The opponent rating */
    private Player $opponent;

    /** @var float The player score */
    private float $score1;

    /** @var float The opponent score */
    private float $score2;

    /**
     * constructor.
     *
     * @param Player $player The player rating
     * @param Player $opponent The opponent rating
     * @param float $score1 The player score
     * @param float $score2 The opponent score
     */
    public function __construct(Player $player, Player $opponent, float $score1, float $score2)
    {
        $this->player = $player;
        $this->opponent = $opponent;
        $this->score1 = $score1;
        $this->score2 = $score2;
    }

    /**
     * Get the 1st player result
     *
     * @return float The 1st player result (0 for a loss, 0.5 for a draw, and 1 for a win)
     */
    public function getResult(): float
    {
        $diff = ($this->score1 - $this->score2);

        if ($diff < 0) {
            return self::RESULT_LOSS;
        }
        if ($diff > 0) {
            return self::RESULT_WIN;
        }
        return self::RESULT_DRAW;
    }

    /**
     * Get the 1st player rating
     *
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Get the 2nd player rating
     *
     * @return Player
     */
    public function getOpponent(): Player
    {
        return $this->opponent;
    }
}
