<?php

declare(strict_types=1);

namespace Youhey\Glicko2;

/**
 * Calculation result
 */
class CalculationResult
{
    /** @var float A rating μ */
    private float $mu;

    /** @var float A rating deviation φ */
    private float $phi;

    /** @var float A rating volatility σ */
    private float $sigma;

    /**
     * constructor.
     *
     * @param float $mu
     * @param float $phi
     * @param float $sigma
     */
    public function __construct(float $mu, float $phi, float $sigma)
    {
        $this->mu = $mu;
        $this->phi = $phi;
        $this->sigma = $sigma;
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
}
