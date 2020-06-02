<?php

namespace App\Service\Parser;

use App\ValueObject\Currency;
use App\ValueObject\Money;
use Sabre\Xml\Reader;

class EcbParser implements ParserStrategyInterface
{
    const FIND_SELECTOR = '{http://www.ecb.int/vocabulary/2002-08-01/eurofxref}Cube';

    public function parse(string $html): array
    {
        try {
            $reader = new Reader();
            $reader->xml($html);
            $data = $reader->parse();

            if (empty($data['value'])) {
                return [];
            }

            $result = [];

            foreach ($data['value'] as $values) {
                if ($values['name'] !== self::FIND_SELECTOR || empty($values['value'][0]['value'])) {
                    continue;
                }

                foreach ($values['value'][0]['value'] as $value) {
                    $result[] = new Money(
                        $value['attributes']['rate'],
                        new Currency($value['attributes']['currency'])
                    );
                }
            }
            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }
}
