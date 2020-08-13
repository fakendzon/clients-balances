<?php

namespace App;

/**
 * Класс для работы с балнасами клиентов.
 */
class Balance
{
    /**
     * Возравщает таблицу балансов клинетов в таблице html.
     */
    public static function getBalancesTableHtml(array $balances): string
    {
        $tableBalances[] = '<table>';
        $tableBalances[] = '<tr><td>ФИО</td><td>Остаток</td></tr>';
        foreach ($balances as $balance) {
            $tableBalances[] = "<tr><td>{$balance['name']}</td><td>{$balance['balance']}</td></tr>";
        }
        $tableBalances[] = '</table>';

        return implode('', $tableBalances);
    }

    /**
     * Возравщает балансы клиентов с учетом новых транзакций.
     */
    public static function getClientsBalancesByTransactions(array $clients, array $transactions): array
    {
        $transactions = self::groupByArray($transactions, 'id');
        return array_map(function ($item) use ($transactions) {
            $transactionsForClient   = $transactions[$item['id']];
            $sumTrasactionsForClient = array_sum(array_column($transactionsForClient, 'transaction'));
            $item['balance'] = $item['balance'] + $sumTrasactionsForClient;
            return $item;
        }, $clients);
    }

    /**
     * Группирует элементы массива по ключу.
     */
    private static function groupByArray(array $array, string $keyGroup): array
    {
        $result = [];
        foreach ($array as $item) {
            $result[$item[$keyGroup]][] = $item;
        }
        return $result;
    }
}
