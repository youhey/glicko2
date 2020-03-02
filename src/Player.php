<?php

declare(strict_types=1);

namespace Youhey\Glicko2;

/**
 * Player rating
 *
 * > Every player in the Glicko-2 system has a rating, r, a rating deviation, RD, and a rating
 * > volatility σ. The volatility measure indicates the degree of expected fluctuation in a player’s
 * > rating. The volatility measure is high when a player has erratic performances (e.g., when
 * > the player has had exceptionally strong results after a period of stability), and the volatility
 * > measure is low when the player performs at a consistent level. As with the original Glicko
 * > system, it is usually informative to summarize a player’s strength in the form of an interval
 * > (rather than merely report a rating). One way to do this is to report a 95% confidence
 * > interval. The lowest value in the interval is the player’s rating minus twice the RD, and the
 * > highest value is the player’s rating plus twice the RD. So, for example, if a player’s rating
 * > is 1850 and the RD is 50, the interval would go from 1750 to 1950. We would then say
 * > that we’re 95% confident that the player’s actual strength is between 1750 and 1950. When
 * > a player has a low RD, the interval would be narrow, so that we would be 95% confident
 * > about a player’s strength being in a small interval of values. The volatility measure does not
 * > appear in the calculation of this interval.
 */
class Player
{
    /** @var float Default rating */
    public const DEFAULT_RATING = 1500.0;

    /** @var float Default rating deviation */
    public const DEFAULT_RATING_DEVIATION = 350.0;

    /** @var float Default rating volatility */
    public const DEFAULT_RATING_VOLATILITY = 0.06;

    /** @var float Scaling factor */
    private const RATIO = 173.7178;

    /** @var float A rating r */
    private float $r;

    /** @var float A rating μ */
    private float $mu;

    /** @var float A rating deviation RD */
    private float $rd;

    /** @var float A rating deviation φ */
    private float $phi;

    /** @var float A rating volatility σ */
    private float $sigma;

    /**
     * constructor.
     *
     * @param float $r A rating r
     * @param float $rd A rating deviation RD
     * @param float $sigma A rating volatility σ
     */
    public function __construct(
        float $r = self::DEFAULT_RATING,
        float $rd = self::DEFAULT_RATING_DEVIATION,
        float $sigma = self::DEFAULT_RATING_VOLATILITY
    ) {
        $this->setRating($r);
        $this->setRatingDeviation($rd);
        $this->setRatingVolatility($sigma);
    }

    /**
     * Set a rating r
     *
     * @param float $r A rating r
     */
    private function setRating(float $r): void
    {
        $this->r = $r;
        $this->mu = (($this->r - self::DEFAULT_RATING) / self::RATIO);
    }

    /**
     * Set a rating μ
     *
     * @param float $mu A rating μ
     */
    private function setRatingMu(float $mu): void
    {
        $this->mu = $mu;
        $this->r = (($this->mu * self::RATIO) + self::DEFAULT_RATING);
    }

    /**
     * Set a rating deviation RD
     *
     * @param float $rd A rating deviation RD
     */
    private function setRatingDeviation(float $rd): void
    {
        $this->rd = $rd;
        $this->phi = ($this->rd / self::RATIO);
    }

    /**
     * Set a rating deviation φ
     *
     * @param float $phi A rating deviation φ
     */
    private function setRatingDeviationPhi(float $phi): void
    {
        $this->phi = $phi;
        $this->rd = ($this->phi * self::RATIO);
    }

    /**
     * Set a rating volatility σ
     *
     * @param float $sigma A rating volatility σ
     */
    private function setRatingVolatility(float $sigma): void
    {
        $this->sigma = $sigma;
    }

    /**
     * Get a rating r
     *
     * @return float
     */
    public function getRating(): float
    {
        return $this->r;
    }

    /**
     * Get a rating μ
     *
     * @return float
     */
    public function getRatingMu(): float
    {
        return $this->mu;
    }

    /**
     * Get a rating deviation RD
     *
     * @return float
     */
    public function getRatingDeviation(): float
    {
        return $this->rd;
    }

    /**
     * Get a rating deviation φ
     *
     * @return float
     */
    public function getRatingDeviationPhi(): float
    {
        return $this->phi;
    }

    /**
     * Get a rating deviation φ
     *
     * @return float
     */
    public function getRatingVolatility(): float
    {
        return $this->sigma;
    }

    /**
     * Update the rating from the calculation result
     *
     * @param CalculationResult $calculationResult
     */
    public function updateRating(CalculationResult $calculationResult): void
    {
        $this->setRatingMu($calculationResult->getRatingMu());
        $this->setRatingDeviationPhi($calculationResult->getRatingDeviationPhi());
        $this->setRatingVolatility($calculationResult->getRatingVolatility());
    }
}
