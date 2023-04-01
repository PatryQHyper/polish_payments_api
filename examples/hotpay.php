<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 14:12
 */

require 'base.php';

const SECRET = '';
const PASSWORD = '';

$hotpay = $polishPaymentsApi->online(new \PatryQHyper\Payments\Providers\HotPay(SECRET, PASSWORD, new \PatryQHyper\Payments\Providers\Miscellaneous\HotPay\PaySafeCardMethod()));

/** @var \PatryQHyper\Payments\Providers\HotPay $hotpay */
$hotpay->setAmount(15);
$hotpay->setDescription('description');
$hotpay->setRedirectUrl('https://google.com');
$hotpay->setOrderId('oid');
print_r($hotpay->generatePayment());