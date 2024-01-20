<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 14:12
 */

require 'base.php';

const SHOP_ID = '';
const SHOP_KEY = '';

$cashbill = $polishPaymentsApi->online(new \PatryQHyper\Payments\Providers\CashBillProvider(SHOP_ID, SHOP_KEY, new \PatryQHyper\Payments\Providers\Miscellaneous\CashBill\TestEnvironment()));

/** @var \PatryQHyper\Payments\Providers\CashBillProvider $cashbill */
$ipn = $cashbill->handleNotification($_GET);
print_r($ipn);