<?php
require_once dirname(__dir__,2).'/database/ConnectDB.php';

class CurrencyApi
{
    public static function getExchangeRatesFromNBP()
    {
        try {
            // Kod do pobrania kursów walut z API NBP
            $url = 'https://api.nbp.pl/api/exchangerates/tables/a/';
            $response = file_get_contents($url);

            if ($response === false) {
                throw new Exception("Błąd podczas pobierania danych z API NBP");
            }

            $data = json_decode($response, true);

            if ($data === null) {
                throw new Exception("Błąd podczas przetwarzania danych z API NBP");
            }

            // Przetwarzanie danych i zwracanie kursów walut
            $exchangeRates = array();

            foreach ($data[0]['rates'] as $rate) {
                $currency = $rate['code'];
                $exchangeRate = $rate['mid'];
                $exchangeRates[$currency] = $exchangeRate;
            }

            return $exchangeRates;

        } catch (Exception $e) {
            // Obsługa błędu
            echo "Błąd: " . $e->getMessage();
            exit;
        }
    }

    public static function saveExchangeRatesToDatabase($exchangeRates)
{
    try {
        $db = new ConnectDB();
        $conn = $db->getConnectionDB();

        foreach ($exchangeRates as $currencyCode => $exchangeRate) {
            $currencyCode = $conn->real_escape_string($currencyCode);
            $exchangeRate = $conn->real_escape_string($exchangeRate);

            $query = "SELECT currency_code FROM exchange_rates WHERE currency_code = '$currencyCode'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $query = "UPDATE exchange_rates SET exchange_rate = '$exchangeRate', timestamp = NOW() WHERE currency_code = '$currencyCode'";
            } else {
                $query = "INSERT INTO exchange_rates (currency_code, exchange_rate, timestamp)
                          VALUES ('$currencyCode', '$exchangeRate', NOW())";
            }

            // Wykonanie zapytania do bazy danych
            $conn->query($query);
        }

        $conn->close();

    } catch (Exception $e) {
        // Obsługa błędu
        echo "Błąd: " . $e->getMessage();
        exit;
    }
}

}

// Pobranie kursów walut z API NBP
$exchangeRates = CurrencyApi::getExchangeRatesFromNBP();

// Zapisanie kursów walut do bazy danych
CurrencyApi::saveExchangeRatesToDatabase($exchangeRates);
