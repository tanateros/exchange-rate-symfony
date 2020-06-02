<?php

namespace App\Service\Parser;

interface ParserStrategyInterface
{
    public function parse(string $html): array;
}
