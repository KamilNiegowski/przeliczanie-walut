<?php
require_once 'database/databaseoperations.php';

class ConverterTableGenerator
{
    public static function generateTable()
    {

        $db = new Database();

        $conversions = $db->fetchLatestConversions();

        // Generowanie tabeli z wynikami przeliczenia walut
        $table = '<h2>Ostatnie przeliczenia waluty:</h2>';
        $table .= '<table>';
        $table .= '<tr><th>ID</th><th>Kwota</th><th>Waluta źródłowa</th><th>Waluta docelowa</th><th>Przeliczona kwota</th><th>Data</th></tr>';

        foreach ($conversions as $conversion) {
            $table .= '<tr>';
            $table .= '<td>' . $conversion['id'] . '</td>';
            $table .= '<td>' . $conversion['amount'] . '</td>';
            $table .= '<td>' . $conversion['source_currency'] . '</td>';
            $table .= '<td>' . $conversion['target_currency'] . '</td>';
            $table .= '<td>' . $conversion['converted_amount'] . '</td>';
            $table .= '<td>' . $conversion['timestamp'] . '</td>';
            $table .= '</tr>';
        }

        $table .= '</table>';

        return $table;
    }
}
