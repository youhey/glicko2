<?php

declare(strict_types=1);

namespace Youhey\Glicko2;

final class Player
{
    public const DEFAULT_R = 1500;
    public const DEFAULT_RD = 350;
    public const DEFAULT_SIGMA = 0.06;

    private const CONVERT = 173.7178;

    /**
     * A rating r
     *
     * @var float
     */
    private float $r;

    /** A rating μ
     *
     * @var float
     */
    private float $mu;

    /**
     * A rating deviation RD
     *
     * @var float
     */
    private float $RD;

    /**
     * A rating deviation φ
     *
     * @var float
     */
    private float $phi;

    /**
     * A rating volatility σ
     *
     * @var float
     */
    private float $sigma;

    /**
     * @param float $r
     * @param float $RD
     * @param float $sigma
     */
    public function __construct(
        float $r = self::DEFAULT_R,
        float $RD = self::DEFAULT_RD,
        float $sigma = self::DEFAULT_SIGMA
    ) {
        $this->setR($r);
        $this->setRD($RD);
        $this->setSigma($sigma);
    }

    /**
     * @param float $r
     */
    private function setR(float $r): void
    {
        $this->r = $r;
        $this->mu = ($this->r - self::DEFAULT_R) / self::CONVERT;
    }

    /**
     * @param float $mu
     */
    private function setMu(float $mu): void
    {
        $this->mu = $mu;
        $this->r = $this->mu * self::CONVERT + self::DEFAULT_R;
    }

    /**
     * @param float $RD
     */
    private function setRD(float $RD): void
    {
        $this->RD = $RD;
        $this->phi = $this->RD / self::CONVERT;
    }

    /**
     * @param float $phi
     */
    private function setPhi(float $phi): void
    {
        $this->phi = $phi;
        $this->RD = $this->phi * self::CONVERT;
    }

    /**
     * @param float $sigma
     */
    private function setSigma(float $sigma): void
    {
        $this->sigma = $sigma;
    }

    /**
     * @return float
     */
    public function getR(): float
    {
        return $this->r;
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
    public function getRd(): float
    {
        return $this->RD;
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

    /**
     * @param CalculationResult $calculationResult
     */
    public function loadFromCalculationResult(CalculationResult $calculationResult): void
    {
        $this->setMu($calculationResult->getMu());
        $this->setPhi($calculationResult->getPhi());
        $this->setSigma($calculationResult->getSigma());
    }
}
