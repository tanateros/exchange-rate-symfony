<?php

namespace App\Service\Parser;

class RatesContextParser
{
    private $strategy;

    /**
     * RatesContextParser constructor.
     * @param ParserStrategyInterface $strategy
     */
    public function __construct(ParserStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param $html
     * @return array
     */
    public function doParse($html): array
    {
        echo 'Find in ' . get_class($this->strategy) . PHP_EOL;
        $found = $this->strategy->parse($html);
        echo 'Found ' . count($found) . PHP_EOL;

        return $found;
    }
}
