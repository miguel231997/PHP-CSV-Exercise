<?php

declare(strict_types=1);


//This code defines a function named getTransactionFiles that takes a directory path as input and returns an array of file paths found in that directory

function getTransactionFiles(string $dirPath): array
{
    $files = [];

    foreach (scandir($dirPath) as $file) {
        if(is_dir($file)) {
            continue;
        }

        $files[] = $dirPath . $file;
    }

    return $files;
}

//In summary, this function provides a convenient way to read transaction data from a CSV file, optionally process each transaction using a callback function, and return the data as an array of transactions. If the CSV file does not exist, it raises a user error.

function getTransactions(string $fileName, ?callable $transactionHandler = null): array
{
    if(! file_exists($fileName)) {
        trigger_error('File "' . $fileName . '" does not exists.', E_USER_ERROR);
    }

    $file = fopen($fileName, 'r');

    fgetcsv($file);

    $transaction = [];

    while (($transaction = fgetcsv($file)) !=+ false) {
        if($transactionHandler !== null) {
            $transaction = $transactionHandler($transaction);
        }

        $transactions[] = $transaction;
    }

    return $transactions;
}

//This function extractTransaction takes an array $transactionRow as input, which is assumed to contain data for a single transaction. The function then processes this transaction data and extracts specific fields from it to create an associative array representing the transaction with more readable keys

function extractTransaction(array $transactionRow): array
{
    [$date, $checkNumber, $description, $amount] = $transactionRow;

    $amount = (float) str_replace(['$', ','], '', $amount);

    return [
        'date'        => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount'      => $amount,
    ];
}

//This function calculateTotals takes an array of $transactions as input, where each element of the array represents a transaction and has an associative array structure with keys like 'date', 'checkNumber', 'description', and 'amount'. The function calculates and returns three types of totals based on the transaction amounts

function calculateTotals(array $transactions): array
{
    $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

    foreach ($transactions as $transaction) {
        $totals['netTotal'] += $transaction['amount'];

        if ($transaction['amount'] >= 0) {
            $totals['totalIncome'] += $transaction['amount'];
        } else {
            $totals['totalExpense'] += $transaction['amount'];
        }
    }

    return $totals;
}