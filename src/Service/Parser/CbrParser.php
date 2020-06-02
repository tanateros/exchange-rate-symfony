<?php

namespace App\Service\Parser;

use App\ValueObject\Currency;
use App\ValueObject\Money;
use Sabre\Xml\Reader;

class CbrParser implements ParserStrategyInterface
{
    protected const CHAR_CODE_NODE_NAME = '{}CharCode';
    protected const NOMINAL_NODE_NAME = '{}Nominal';
    protected const VALUE_NODE_NAME = '{}Value';

    public function parse(string $html): array
    {
        try {
            $reader = new Reader();
            $reader->xml($html);
            $data = $reader->parse();
            $result = [];

            if (!empty($data['value'])) {
                foreach ($data['value'] as $values) {
                    $rateData = $values['value'];

                    foreach ($rateData as $key => $item) {
                        if ($item['name'] === self::CHAR_CODE_NODE_NAME) {
                            $code = $item['value'];
                        } else if ($item['name'] === self::NOMINAL_NODE_NAME) {
                            $nominal = (int)$item['value'];
                        } else if ($item['name'] === self::VALUE_NODE_NAME) {
                            $value = (float)str_replace(',', '.', $item['value']);
                        }
                    }

                    if (!empty($code) && !empty($value) && !empty($nominal)) {
                        $result[] = new Money($value / $nominal, new Currency($code));
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }
}
