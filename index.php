<?php
session_start();
require_once 'currency/currencyoperations/CurrencyConverter.php';
require_once 'tables/CurrencyTableGenerator.php';
require_once 'tables/ConverterTableGenerator.php';

// Pobranie listy walut z bazy danych
$db = new Database();
$data = $db->fetchData('exchange_rates');

// Tworzenie tablicy z nazwami walut i ich wartościami
$currencies = [];
$exchangeRates = [];

foreach ($data as $row) {
    $currencies[] = $row['currency_code'];
    $exchangeRates[$row['currency_code']] = $row['exchange_rate'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Przelicznik walut</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="current-currency">
        <h1>Aktualne kursy walut</h1>

        <?php
            echo CurrencyTableGenerator::generateTable();
        ?>

    </div>
    <div class="currency-convert">
        <h1>Przelicznik walut</h1>
        <form method="POST" action="database/databaseoperations.php">
            <label for="amount">Kwota:</label>
            <input type="text" id="amount" name="amount" pattern="\d+(\.\d+)?" required>

            <label for="source_currency">Waluta źródłowa:</label>
            <select id="source_currency" name="source_currency" required>
                <?php foreach ($currencies as $currency): ?>
                    <option value="<?php echo $currency; ?>"><?php echo $currency; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="target_currency">Waluta docelowa:</label>
            <select id="target_currency" name="target_currency" required>
                <?php foreach ($currencies as $currency): ?>
                    <option value="<?php echo $currency; ?>"><?php echo $currency; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Przelicz</button>
        </form>

        <?php
            if (isset($_SESSION['messageConvertedAmount'])) {
                $message = $_SESSION['messageConvertedAmount'];
                unset($_SESSION['messageConvertedAmount']); // Usunięcie zmiennej sesyjnej po odczytaniu
                echo $message;
            }

            echo ConverterTableGenerator::generateTable();

        ?>
    </div>
</body>
</html>

