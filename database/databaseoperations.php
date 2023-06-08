<?php
require_once 'connectDB.php';
require_once dirname(__DIR__) . '/currency/currencyoperations/CurrencyConverter.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Database {
    private $connection;

    public function __construct()
    {
        $ConnectDB = new ConnectDB();
        $this->connection = $ConnectDB->getConnectionDB();
    }

    public function saveData($amount, $sourceCurrency, $targetCurrency, $convertedAmount): void
    {
        try {
            // Kod zapisujący dane do bazy danych
            $sqlSaveData = "INSERT INTO conversions (amount, source_currency, target_currency, converted_amount)
                    VALUES ($amount, '$sourceCurrency', '$targetCurrency', $convertedAmount)";

            if ($this->connection->query($sqlSaveData) !== true) {
                throw new Exception($this->connection->error);
            }

            $_SESSION['messageConvertedAmount'] = number_format(floatval($amount), 2, ',', ' ') . ' ' . $sourceCurrency . ' to w przeliczeniu ' . number_format($convertedAmount, 2, ',', ' ') . ' ' . $targetCurrency;
            header('Location: http://localhost/api-waluty/');
            exit;
        } catch (Exception $e) {
            echo "Błąd zapisu do bazy danych: " . $e->getMessage();
            exit;
        }
    }

    public function fetchData($table_name)
    {
        // Kod pobierający dane z bazy danych
        $sqlFetchData = "SELECT * FROM $table_name ";
        $result = $this->connection->query($sqlFetchData);

        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        } else {
            return [];
        }
    }
    public function fetchLatestConversions()
    {
    $sqlFetchLatestConvesrions = "SELECT * FROM conversions ORDER BY timestamp DESC ";
    $result = $this->connection->query($sqlFetchLatestConvesrions);

    if ($result->num_rows > 0) {
        $conversions = $result->fetch_all(MYSQLI_ASSOC);
        return $conversions;
    } else {
        return [];
    }
    }
}

// Utwórz instancję klasy Database
$db = new Database();

// Sprawdź, czy dane zostały przekazane z formularza
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Pobierz dane z formularza
    $amount = $_POST["amount"];
    $sourceCurrency = $_POST["source_currency"];
    $targetCurrency = $_POST["target_currency"];

    // Wykonaj konwersję walut
    $convertedAmount = CurrencyConverter::convertCurrency($amount, $sourceCurrency, $targetCurrency);

    // Zapisz dane do bazy danych
    $db->saveData($amount, $sourceCurrency, $targetCurrency, $convertedAmount);
}
