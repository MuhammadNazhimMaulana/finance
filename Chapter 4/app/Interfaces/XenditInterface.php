<?php

namespace App\Interfaces;

interface XenditInterface
{
    public static function getBalance(string $type);

    public static function getDisbursement(string $id);

    public static function getInvoice(string $id);

    public static function account();

    public static function banks();

    public static function createInvoice(array $params);

    public static function createDisbursement(array $params);

    public static function reports();

    public static function createReport(string $type, string $from, string $to);

    public static function getReport(string $id);
}
