<?php
require_once 'database/databaseoperations.php';

class CurrencyTableGenerator
{
    public static function generateTable()
    {
        $db = new Database();
        $data = $db->fetchData('exchange_rates');

        // Generowanie tabeli z aktualnymi kursami walut

        if (!empty($data)) {
            $table = '<table>';
            $table .= '<tr><th>Nazwa waluty</th><th>Kurs wymiany</th><th>Data</th></tr>';

            foreach ($data as $row) {
                $table .= '<tr>';
                $table .= '<td>' . $row['currency_code'] . '</td>';
                $table .= '<td>' . $row['exchange_rate'] . '</td>';
                $table .= '<td>' . $row['timestamp'] . '</td>';
                $table .= '</tr>';
            }

            $table .= '</table>';
        } else {
            $table = 'Brak danych do wyświetlenia.';
        }

        return $table;
    }
}
