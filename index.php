<?php

use Symfony\Component\HttpFoundation\Request;
use App\FileReader;
use App\Balance;

const COUNT_LIST_BALNCES_FILE = 2;
require __DIR__.'/vendor/autoload.php';

$request = Request::createFromGlobals();

if ($request->request->get('submit') === 'file_upload') {
    try {
        $fileReader  = new FileReader($request->files->get('balances'), 'xlsx');
        $fileContent = $fileReader->getContent();

        if (count($fileContent) < COUNT_LIST_BALNCES_FILE) {
            throw new Exception("Неварный формат файла, " . COUNT_LIST_BALNCES_FILE . " листов");
        }

        $clients      = array_map(function ($item) {
            return ['id' => $item[0], 'name' => $item[1], 'balance' => $item[2]];
        }, $fileContent[0]);

        $transactions = array_map(function ($item) {
            return ['id' => $item[0], 'transaction' => $item[1]];
        }, $fileContent[1]);

        $newBalances    = Balance::getClientsBalancesByTransactions($clients, $transactions);
        $tableBalances  = Balance::getBalancesTableHtml($newBalances);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

require 'index.html';
