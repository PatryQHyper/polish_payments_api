<?php global $polishPaymentsApi;

require 'base.php';

const SHOP_ID = 'pay.pathyper.pl';
const SHOP_KEY = '5b54c89a53aca5c34ecc6c440dff53b7';

$cashbill = $polishPaymentsApi->online(new \PatryQHyper\Payments\Providers\CashBill\CashBillProvider(SHOP_ID, SHOP_KEY, \PatryQHyper\Payments\Providers\CashBill\Environment::TEST));

/** @var \PatryQHyper\Payments\Providers\CashBillProvider $cashbill */
$cashbill->setTitle('test');
$cashbill->setAmount(15);
$payment = $cashbill->generatePayment();
echo $payment;
print_r($cashbill->generatePayment());