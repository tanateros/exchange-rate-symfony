<?php

namespace App\DBAL;

use App\Service\Parser\CbrParser;
use App\Service\Parser\EcbParser;

class EnumRateSourceType extends EnumType
{
    public const CBR = 'CBR';
    public const ECB = 'ECB';
    public const RATE_SOURCE = [
        self::CBR => [
            'url' => 'https://www.cbr.ru/scripts/XML_daily.asp',
            'class' => CbrParser::class,
            'crossCurrency' => 'RUB',
        ],
        self::ECB => [
            'url' => 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml',
            'class' => EcbParser::class,
            'crossCurrency' => 'EUR',
        ],
    ];
    public const VALUES = [self::CBR, self::ECB];
    protected $name = 'enumsource';
    protected $values = self::VALUES;
}
