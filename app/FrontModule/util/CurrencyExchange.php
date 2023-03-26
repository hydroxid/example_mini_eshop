<?php

namespace App\Util;

use Nette;

class CurrencyExchange
{
    use Nette\SmartObject;

    private $sourceData = null;
    private $currency = null;

    /**
    * get exchange for currency
    *
    * @param string $currency currency name
    * @return float
    * @author hydroxid
    */
    public function getExchangeFor(string $currency) : ?float
    {
        $this->currency = $currency;
        $this->sourceData = file_get_contents(EXCHANGE_SOURCE);

        // if source data exists
        if ($this->sourceData) {

            // exchange for currency found
            if ($exchange = $this->parseData()) {
                return $this->convertToFloat($exchange);
            }

        }

        return null;
    }

    /**
    * parse data line by line to get exchange for currency
    *
    * @return string
    * @author hydroxid
    */
    public function parseData() : ?string
    {
        // array liny by line
        $lines = explode(PHP_EOL, $this->sourceData);
        foreach ($lines as $line) {

            // if line contain $this->currency
            if (str_contains($line, $this->currency)) {
              
                $items = explode('|', $line);
                // last index with
                if (isset($items[4])) {
                    return (string) $items[4];
                }

            }

        }
        return null;
    }

    /**
    * convert string with comma to float
    *
    * @param string $exchange currency exchange
    * @return float
    * @author hydroxid
    */
    public function convertToFloat(string $exchange) : float
    {
        return floatval(str_replace(',', '.', $exchange));
    }
}
