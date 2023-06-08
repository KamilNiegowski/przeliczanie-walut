<?php
require_once dirname(__dir__,2).'/database/databaseoperations.php';
require_once dirname(__dir__).'/connectAPI/CurrencyAPI.php';

class CurrencyConverter {
    public static function convertCurrency($amount, $sourceCurrency, $targetCurrency) {
        // Kod przeliczający podaną kwotę z jednej waluty na drugą
        $exchangeRates = CurrencyAPI::getExchangeRatesFromNBP();

        if (isset($exchangeRates[$sourceCurrency]) && isset($exchangeRates[$targetCurrency])) {
            $sourceRate = $exchangeRates[$sourceCurrency];
            $targetRate = $exchangeRates[$targetCurrency];
            $convertedAmount = $amount / $targetRate * $sourceRate;
            return $convertedAmount;
        } else {
            return null;
        }
    }
}
