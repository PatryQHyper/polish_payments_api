<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 14:12
 */

require 'base.php';

const API_KEY = 'knewKRx5';
const API_PASSWD = 'ysPgB5o7rdCTnWpSOQzVe2M1490EuqI3';
const SERVICE_ID = '214';
const SERVICE_HASH = 'wx1C9ZzlG3fPLDjd';

$simpay = $polishPaymentsApi->online(new \PatryQHyper\Payments\Providers\SimPayDirectBilling(API_KEY, API_PASSWD, SERVICE_ID, SERVICE_HASH));

/** @var \PatryQHyper\Payments\Providers\SimPayDirectBilling $simpay */
$simpay->setAmount(15);
$simpay->setDescription('Hej Rafał. Hej Krzysiu');
$simpay->setControl('asdsad');
$simpay->setReturnFail('https://dpay.pl');
$simpay->setReturnSuccess('https://hotpay.pl');
$simpay->setPhoneNumber('123123123');
$simpay->setAmountType('net');
$simpay->setSteamId('76561199012591716');
print_r($simpay->generatePayment());