--
-- Struktura tabeli dla tabeli `conversions`
--

CREATE TABLE `conversions` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `source_currency` varchar(3) NOT NULL,
  `target_currency` varchar(3) NOT NULL,
  `converted_amount` decimal(10,2) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `id` int(11) NOT NULL,
  `currency_code` varchar(3) DEFAULT NULL,
  `exchange_rate` decimal(10,4) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
