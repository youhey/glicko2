<?php

declare(strict_types=1);

namespace Youhey\Glicko2;

final class Match
{
    /**
     * @var Player
     */
    private Player $player1;

    /**
     * @var Player
     */
    private Player $player2;

    /**
     * @var float
     */
    private float $score1;

    /**
     * @var float
     */
    private float $score2;

    private const RESULT_WIN = 1.0;
    private const RESULT_DRAW = 0.5;
    private const RESULT_LOSS = 0.0;

    /**
     * @param Player $player1
     * @param Player $player2
     * @param float $score1
     * @param float $score2
     */
    public function __construct(Player $player1, Player $player2, float $score1, float $score2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->score1 = $score1;
        $this->score2 = $score2;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        $diff = $this->score1 - $this->score2;
        switch (true) {
            case $diff < 0 :
                $matchScore = self::RESULT_LOSS;
                break;
            case $diff > 0 :
                $matchScore = self::RESULT_WIN;
                break;
            default :
                $matchScore = self::RESULT_DRAW;
                break;
        }
        return (float)$matchScore;
    }

    /**
     * @return Player
     */
    public function getPlayer1(): Player
    {
        return $this->player1;
    }

    /**
     * @return Player
     */
    public function getPlayer2(): Player
    {
        return $this->player2;
    }
}
