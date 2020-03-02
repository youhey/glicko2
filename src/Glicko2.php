<?php

declare(strict_types=1);

namespace Youhey\Glicko2;

/**
 * Glicko-2 rating system
 */
class Glicko2
{
    /** ε */
    private const CONVERGENCE_TOLERANCE = 0.000001;

    /** pow M_PI, 2 */
    private const PI_2 = 9.8696044;

    /** @var float The tau parameter τ */
    private float $tau;

    /**
     * constructor.
     *
     * @param float $tau The tau parameter,
     *     which controls the change in the player volatility across time.
     *     Smaller values prevent the volatility measures from changing by large amounts.
     *     Must be a single number. Mark Glickman suggests a value between 0.3 and 1.2.
     *     A non-pos
     */
    public function __construct(float $tau = 0.5)
    {
        $this->tau = $tau;
    }

    /**
     * Calculate a new rating from the match result
     *
     * @param Match $match The match result
     */
    public function calculateMatch(Match $match): void
    {
        $player1 = $match->getPlayer();
        $player2 = $match->getOpponent();

        $result = $match->getResult();

        $calculationResult1 = $this->calculatePlayer($player1, $player2, $result);
        $calculationResult2 = $this->calculatePlayer($player2, $player1, (1.0 - $result));

        $player1->updateRating($calculationResult1);
        $player2->updateRating($calculationResult2);
    }

    /**
     * Calculate new player rating
     *
     * @param Player $player1
     * @param Player $player2
     * @param float $score
     *
     * @return CalculationResult
     */
    private function calculatePlayer(Player $player1, Player $player2, float $score): CalculationResult
    {
        $phi = $player1->getRatingDeviationPhi();
        $mu = $player1->getRatingMu();
        $sigma = $player1->getRatingVolatility();

        $phiJ = $player2->getRatingDeviationPhi();
        $muJ = $player2->getRatingMu();

        $v = $this->v($phiJ, $mu, $muJ);

        $delta = $this->delta($phiJ, $mu, $muJ, $score);

        $sigmaP = $this->sigmaP($delta, $sigma, $phi, $phiJ, $mu, $muJ);
        $phiS = $this->phiS($phi, $sigmaP);
        $phiP = $this->phiP($phiS, $v);
        $muP = $this->muP($mu, $muJ, $phiP, $phiJ, $score);

        return new CalculationResult($muP, $phiP, $sigmaP);
    }

    /**
     * Step 3 Estimated variance
     *
     * @param float $phiJ opponent rating deviation
     * @param float $mu player rating
     * @param float $muJ opponent rating
     *
     * @return float
     */
    private function v(float $phiJ, float $mu, float $muJ): float
    {
        $g = $this->g($phiJ);
        $e = $this->e($mu, $muJ, $phiJ);

        return (1.0 / (($g ** 2) * $e * (1.0 - $e)));
    }

    /**
     * g(ϕj)
     *
     * @param float $phi rating deviation
     *
     * @return float
     */
    private function g(float $phi): float
    {
        return (1.0 / sqrt((1.0 + (3.0 * ($phi ** 2)) / self::PI_2)));
    }

    /**
     * E(μ,μj,ϕj)
     *
     * @param float $mu player rating
     * @param float $muJ opponent rating
     * @param float $phiJ opponent rating deviation
     *
     * @return float
     */
    private function e($mu, $muJ, $phiJ): float
    {
        return (1.0 / (1.0 + exp((-1 * $this->g($phiJ) * ($mu - $muJ)))));
    }

    /**
     * Step 4 Estimated improvement in rating
     *
     * Δ
     *
     * @param float $phiJ opponent rating deviation
     * @param float $mu player rating
     * @param float $muJ opponent rating
     * @param float $score player score
     *
     * @return float
     */
    private function delta(float $phiJ, float $mu, float $muJ, float $score): float
    {
        return ($this->v($phiJ, $mu, $muJ) * $this->g($phiJ) * ($score - $this->e($mu, $muJ, $phiJ)));
    }

    /**
     * Step 5 New sigma

     * @param float $delta Δ
     * @param float $sigma player sigma
     * @param float $phi player rating deviation
     * @param float $phiJ opponent rating deviation
     * @param float $mu player rating
     * @param float $muJ opponent rating
     *
     * @return float
     */
    private function sigmaP(float $delta, float $sigma, float $phi, float $phiJ, float $mu, float $muJ): float
    {
        $fX = function ($x, $delta, $phi, $v, $a, $tau) {
            return (
                (exp($x) * (($delta ** 2) - ($phi ** 2) - $v - exp($x)) / (2 * (($phi ** 2) + $v + exp($x) ** 2)))
                - (($x - $a) / ($tau ** 2))
            );
        };

        $a = log(($sigma ** 2));
        $v = $this->v($phiJ, $mu, $muJ);

        $A = $a;
        if (($delta ** 2) > (($phi ** 2) + $v)) {
            $B = log((($delta ** 2) - ($phi ** 2) - $v));
        } else {
            $k = 1;
            while ($fX(($a - ($k * abs($this->tau))), $delta, $phi, $v, $a, $this->tau) < 0.0) {
                $k++;
            }
            $B = ($a - ($k * abs($this->tau)));
        }

        $fA = $fX($A, $delta, $phi, $v, $a, $this->tau);
        $fB = $fX($B, $delta, $phi, $v, $a, $this->tau);

        while (abs($B - $A) > self::CONVERGENCE_TOLERANCE) {
            $C = ($A + $fA * ($A - $B) / ($fB - $fA));
            $fC = $fX($C, $delta, $phi, $v, $a, $this->tau);

            if (($fC * $fB) < 0.0) {
                $A = $B;
                $fA = $fB;
            } else {
                $fA = ($fA / 2.0);
            }

            $B = $C;
            $fB = $fC;
        }

        return exp($A / 2);
    }

    /**
     * Step 6 New rating deviation.
     *
     * @param float $phi
     * @param float $sigma
     *
     * @return float
     */
    private function phiS(float $phi, float $sigma): float
    {
        return sqrt((($phi ** 2) + ($sigma ** 2)));
    }

    /**
     * Step 7 New phi
     *
     * @param float $phi
     * @param float $v
     *
     * @return float
     */
    private function phiP($phi, $v)
    {
        return (1.0 / sqrt(((1.0 / ($phi ** 2) ) + (1.0 / $v))));
    }

    /**
     * New mu
     *
     * @param float $mu
     * @param float $muJ
     * @param float $phiP
     * @param float $phiJ
     * @param float $score
     *
     * @return float
     */
    private function muP(float $mu, float $muJ, float $phiP, float $phiJ, float $score): float
    {
        return ($mu + ($phiP ** 2) * $this->g($phiJ) * ($score - $this->e($mu, $muJ, $phiJ)));
    }
}
