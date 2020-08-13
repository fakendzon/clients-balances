<?php

namespace Tests;

use App\Balance;
use PHPUnit\Framework\TestCase;

class BalancesTest extends TestCase
{
    /**
     * @dataProvider balances
     */
    public function testGetClientsBalancesByTransactions($clients, $transactions, $balances)
    {
        $this->assertTrue(Balance::getClientsBalancesByTransactions($clients, $transactions) === $balances);
    }


    public function balances()
    {
        return [
            [
                [['id' => 1, 'name' => 'client1', 'balance' => 10]],
                [['id' => 1, 'transaction' => -4]],
                [['id' => 1, 'name' => 'client1', 'balance' => 6]],
            ],[
                [['id' => 1, 'name' => 'client1', 'balance' => 10]],
                [['id' => 1, 'transaction' => -4], ['id' => 1, 'transaction' => -4]],
                [['id' => 1, 'name' => 'client1', 'balance' => 2]],
            ],[
                [['id' => 1, 'name' => 'client1', 'balance' => 10], ['id' => 2, 'name' => 'client1', 'balance' => 10]],
                [['id' => 1, 'transaction' => -4], ['id' => 1, 'transaction' => -4], ['id' => 2, 'transaction' => 8]],
                [['id' => 1, 'name' => 'client1', 'balance' => 2], ['id' => 2, 'name' => 'client1', 'balance' => 18]],
            ],[
                [['id' => 3, 'name' => 'client1', 'balance' => 35800]],
                [
                    ['id' => 3, 'transaction' => 350],
                    ['id' => 3, 'transaction' => 6500],
                    ['id' => 3, 'transaction' => -9000],
                    ['id' => 3, 'transaction' => 75000]
                ],
                [['id' => 3, 'name' => 'client1', 'balance' => 108650]],
            ]
        ];
    }
}
