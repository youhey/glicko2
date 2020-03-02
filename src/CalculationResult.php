<?php

declare(strict_types=1);

namespace Youhey\Glicko2;

final class CalculationResult
{
    /**
     * @var float
     */
    private float $mu;

    /**
     * @var float
     */
    private float $phi;

    /**
     * @var float
     */
    private float $sigma;

    /**
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
     * @return float
     */
    public function getMu(): float
    {
        return $this->mu;
    }

    /**
     * @return float
     */
    public function getPhi(): float
    {
        return $this->phi;
    }

    /**
     * @return float
     */
    public function getSigma(): float
    {
        return $this->sigma;
    }
}
