<?php

declare(strict_types=1);


//Both of these functions are useful for formatting common data types in a human-readable way. The formatDollarAmount function is useful for formatting monetary values, while the formatDate function is useful for converting and formatting date strings into a more readable format.


function formatDollarAmount(float $amount): string
{
    $isNegative = $amount < 0;

    return ($isNegative ? '-' : '') . '$' . number_format(abs($amount), 2);
}

function formatDate(string $date): string
{
    return date('M j, Y', strtotime($date));
}