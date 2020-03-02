<?php

declare(strict_types=1);

namespace Youhey\Glicko2;

use ArrayIterator;
use ArrayObject;

final class MatchCollection
{
    /**
     * @var ArrayObject|Match[]
     */
    private ArrayObject $matches;

    public function __construct()
    {
        $this->matches = new ArrayObject();
    }

    /**
     * @param Match $match
     */
    public function addMatch(Match $match): void
    {
        $this->matches->append($match);
    }

    /**
     * @return ArrayIterator
     */
    public function getMatches(): ArrayIterator
    {
        return $this->matches->getIterator();
    }
}
