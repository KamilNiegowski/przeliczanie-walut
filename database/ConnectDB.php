<?php
require_once 'config.php';

class ConnectDB {

    private $connectio;

    public function __construct()
    {
        try {
            // Inicjalizacja połączenia z bazą danych
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        } catch (Exception $e) {

            // Przechwycenie i obsługa błędu połączenia z bazą danych
            echo "Błąd z połączeniem z bazą danych: " . $e->getMessage();
            exit;
        }
    }

    public function getConnectionDB()
    {
        return $this->connection;
    }
}
