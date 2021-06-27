# Polish Payments Providers PHP library

### Supported payment methods:
* SMS Premium
* Bank transfer
* PaySafeCard
* DirectCarrierBilling

### Installation
To install this library, you have to use Composer
```bash
composer require patryqhyper/polish_payments_api
```

### Supported providers
#### SMS
* GetPay

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Sms\GetPaySMS;

$getpay = new GetPaySMS('api_key', 'api_secret');

try {
    $response = $getpay->check('sms_number (int)', 'sms_code');

    if($response) {
        //code is active
    }
    else if($getpay->checkIfUsed()) {
        //code is already used
    }
    else  {
        //invalid sms code
    }
} catch (\PatryQHyper\Payments\Exceptions\SmsException $exception) {
    echo $exception->getMessage();
}
```
* Paybylink

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Sms\PaybylinkSMS;

$pbl = new PaybylinkSMS('user_id (int)', 'service_id (int)');

try {
    $response = $pbl->check('number (int)', 'sms_code');
    if($response) {
        //code is active
    }
    else if($pbl->checkIfUsed()) {
        //code is already used
    }
    else {
        //invalid sms code
    }
} catch (\PatryQHyper\Payments\Exceptions\SmsException $exception) {
    echo $exception->getMessage();
}
```
* MicroSMS

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Sms\MicrosmsSMS;

$msms = new MicrosmsSMS('user_id (int)', 'service_id (int)');

try {
    $response = $msms->check('number (int)', 'sms_code');
    if($response) {
        //code is active
    }
    else if($msms->checkIfUsed()) {
        //code is already used
    }
    else {
        //invalid sms code
    }
} catch (\PatryQHyper\Payments\Exceptions\SmsException $exception) {
    echo $exception->getMessage();
}
```

* HotPay

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Sms\HotPaySMS;

$hotpay = new HotPaySMS('service_secret');

try {
    $response = $hotpay->check('sms_code');
    if($response) {
        //code is active
    }
    else if($hotpay->checkIfUsed()) {
        //code is already used
    }
    else {
        //invalid sms code
    }

} catch (\PatryQHyper\Payments\Exceptions\SmsException $exception) {
    echo $exception->getMessage();
}
```
* CashBill

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Sms\CashBillSMS;

$cashbill = new CashBillSMS('token');

try {
    $response = $cashbill->check('sms_code');
    if($response) {
        //code is active
        //you can also do $response->getNumber(); - to get sms number, that has been used for this code
    }
    else if($cashbill->checkIfUsed()) {
        //code is already used
    }
    else {
        //invalid sms code
    }

} catch (\PatryQHyper\Payments\Exceptions\SmsException $exception) {
    echo $exception->getMessage();
}
```

* Simpay

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Sms\SimpaySMS;

$simpay = new SimpaySMS('api_key', 'api_password');

try {
    $response = $simpay->check('service_id (int)', 'number (int)', 'sms_code');
    if($response)
    {
        //code is active
    }
    else if($simpay->checkIfUsed())
    {
        //code is already used
    }
    else {
        //invalid sms code
    }

} catch (\PatryQHyper\Payments\Exceptions\SmsException $exception) {
    echo $exception->getMessage();
}
```

* Lvlup.pro

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Sms\LvlupSMS;

$simpay = new LvlupSMS('user_id (int)');

try {
    $response = $simpay->check('sms_code', 'number (int)', 'additional description');
    if($response)
    {
        //code is active
    }
    else if($simpay->checkIfUsed())
    {
        //code is already used
    }
    else {
        //invalid sms code
    }

} catch (\PatryQHyper\Payments\Exceptions\SmsException $exception) {
    echo $exception->getMessage();
}
```

#### Bank transfer
* Cashbill
###### Generate payment

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\CashBillTransfer;

try {
    $payment = new CashBillTransfer('shop_id', 'shop_key', 'is_test (bool)');
    $payment->generate('price (float)', 'title', '?additional_data', '?description', '?returnUrl', '?negativeReturnUrl', '?email', '?paymentChannel', '?firstName', '?surname', '?language', '?currency', '?referer');
    echo $payment->getTransactionId(); //generated transaction id
    echo '<br>';
    echo $payment->getTransactionUrl(); //generated transaction url
} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

###### Set redirect urls

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\CashBillTransfer;

try {
    $payment = new CashBillTransfer('shop_id', 'shop_key', 'is_test (bool)');
    $payment->setPaymentUrls('return_url', 'negative_return_url', 'transaction_id');
} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

###### Get payment channels

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\CashBillTransfer;

try {
    $payment = new CashBillTransfer('shop_id', 'shop_key', 'is_test (bool)');
    var_dump($payment->getChannels('language (available pl, en. default pl)'));
} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

###### Get payment info

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\CashBillTransfer;

try {
    $payment = new CashBillTransfer('shop_id', 'shop_key', 'is_test (bool)');
    var_dump($payment->getTransactionInfo('transaction_id'));
} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

* Paybylink

###### Generate transaction
```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\PaybylinkTransfer;

try {
    $payment = new PaybylinkTransfer('shop_id (int)', 'shop_hash');
    $payment->generate('price (float)', '?control', '?description', '?email', '?notifyUrl', '?returnUrlSuccess', '?customFinishNote');

    echo $payment->getTransactionId(); //generated transaction id
    echo '<br>';
    echo $payment->getTransactionUrl(); //generated transaction url
} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

###### Generate notification signature. After generating, you have to check signature sent with request.
```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\PaybylinkTransfer;

try {
    $payment = new PaybylinkTransfer('shop_id (int)', 'shop_hash');
    //if you are using clean php
    $signature = $payment->generateIpnHash(json_decode(file_get_contents('php://input'), true));
    //if you are using Laravel
    $signature = $payment->generateIpnHash((array) $request->all());

} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

* MicroSMS
###### Generate payment
```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\MicrosmsTransfer;

try {
    $payment = new MicrosmsTransfer('user_id (int)', 'shop_id (int)', 'hash', 'use_md5 (bool, default false) (if false, uses sha256)');
    $payment->generate('price (float)', '?control', '?return_urlc', '?return_url', '?description');

    # Redirect to payment page
    header('Location: '.$payment->getRedirectUrl(true)); # parameter in function getRedirectUrl() is adding parameters to url
    
    # OR
    
    # Redirect via FORM
    echo '<form method="GET" action="'.$payment->getRedirectUrl().'">';
    foreach ($payment->getParameters() as $name => $value)
    {
        echo '<input type="hidden" name="'.$name.'" value="'.$value.'">';
    }

    echo '<button type="submit">Pay via MicroSMS</button> </form>';

} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

###### Check IP address
```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\MicrosmsTransfer;

try {
    $payment = new MicrosmsTransfer('user_id (int)', 'shop_id (int)', 'hash', 'use_md5 (bool, default false) (if false, uses sha256)');
    
    if(!$payment->checkIp('ip_address'))
    {
        exit('invalid ip address');
    }

} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

* HotPay
###### Generate payment
```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\HotPayTransfer;

try {
    $payment = new HotPayTransfer('secret', 'notification_password');

    $payment->generate('price (float)', 'service_name', '?redirect_url', '?order_id', '?email', '?personal_data', '?payment_method (available: BLIK and PAYPAL)');

    # Redirect to payment page using GET method
    header('Location: '.$payment->getRedirectUrl(true)); # parameter in function getRedirectUrl() is adding parameters to url

    # OR

    # Redirect via FORM using POST method
    echo '<form method="POST" action="'.$payment->getRedirectUrl().'">';
    foreach ($payment->getParameters() as $name => $value)
    {
        echo '<input type="hidden" name="'.$name.'" value="'.$value.'">';
    }
    echo '<button type="submit">Pay via HotPay</button> </form>';

} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

###### Get signature to notification

```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\Transfer\HotPayTransfer;

try {
    $payment = new HotPayTransfer('secret', 'notification_password');

    $hash = $payment->generateHash($_POST);
} catch (\PatryQHyper\Payments\Exceptions\TransferException $exception) {
    echo $exception->getMessage();
}
```

#### PaySafeCard

* CashBill
###### Generate payment

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\PaySafeCard\CashBillPaySafeCard;

try {
    $payment = new CashBillPaySafeCard('shop_id', 'shop_key', 'is_test (bool)');
    $payment->generate('price (float)', 'title', 'email', '?additional_data', '?description', '?returnUrl', '?negativeReturnUrl', '?firstName', '?surname', '?language', '?currency', '?referer');
    echo $payment->getTransactionId(); //generated transaction id
    echo '<br>';
    echo $payment->getTransactionUrl(); //generated transaction url
} catch (\PatryQHyper\Payments\Exceptions\PaySafeCardException $exception) {
    echo $exception->getMessage();
}
```

###### Set redirect urls

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\PaySafeCard\CashBillPaySafeCard;

try {
    $payment = new CashBillPaySafeCard('shop_id', 'shop_key', 'is_test (bool)');
    $payment->setPaymentUrls('return_url', 'negative_return_url', 'transaction_id');
} catch (\PatryQHyper\Payments\Exceptions\PaySafeCardException $exception) {
    echo $exception->getMessage();
}
```

###### Get payment info

```php
<?php

require('../vendor/autoload.php');

use PatryQHyper\Payments\PaySafeCard\CashBillPaySafeCard;

try {
    $payment = new CashBillPaySafeCard('shop_id', 'shop_key', 'is_test (bool)');
    var_dump($payment->getTransactionInfo('transaction_id'));
} catch (\PatryQHyper\Payments\Exceptions\PaySafeCardException $exception) {
    echo $exception->getMessage();
}
```

* Paybylink

```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\PaySafeCard\PaybylinkPaySafeCard;

try {
    $payment = new PaybylinkPaySafeCard('user_id (int)', 'shop_id (int)', 'shop_pin');

    $payment->generate('price (float)', 'return_ok', 'return_fail', 'notify_url', 'control', '?description');

    # $payment->getPid(); - receive payment id from paybylink
    # $payment->getPaymentUrl(); - receive payment url


} catch (\PatryQHyper\Payments\Exceptions\PaySafeCardException $exception) {
    echo $exception->getMessage();
}
```

* HotPay
###### Generate payment
```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\PaySafeCard\HotPayPaySafeCard;

try {
    $payment = new HotPayPaySafeCard('secret', 'notification_password');

    $payment->generate('price (float)', 'service_name', '?redirect_url', '?order_id', '?email', '?personal_data', '?payment_method (available: BLIK and PAYPAL)');

    # Redirect to payment page using GET method
    header('Location: '.$payment->getRedirectUrl(true)); # parameter in function getRedirectUrl() is adding parameters to url

    # OR

    # Redirect via FORM using POST method
    echo '<form method="POST" action="'.$payment->getRedirectUrl().'">';
    foreach ($payment->getParameters() as $name => $value)
    {
        echo '<input type="hidden" name="'.$name.'" value="'.$value.'">';
    }
    echo '<button type="submit">Pay via HotPay</button> </form>';

} catch (\PatryQHyper\Payments\Exceptions\PaySafeCardException $exception) {
    echo $exception->getMessage();
}
```

###### Get signature to notification

```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\PaySafeCard\HotPayPaySafeCard;

try {
    $payment = new HotPayPaySafeCard('secret', 'notification_password');

    $hash = $payment->generateHash($_POST);
} catch (\PatryQHyper\Payments\Exceptions\PaySafeCardException $exception) {
    echo $exception->getMessage();
}
```

#### Direct Carrier Billing
* SimPay
```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\DirectBilling\SimPayDirectBilling;

try {
    $payment = new SimPayDirectBilling('service_id (int)', 'api_key');

    $payment->generate('control', 'price (float)', '?complete_url', '?failure_url', '?provider');

    # $payment->getTransactionId(); - get transaction id

    # $payment->getTransactionUrl(); - get transaction url

} catch (\PatryQHyper\Payments\Exceptions\DirectBillingException $exception) {
    echo $exception->getMessage();
}
```

* Paybylink
```php
<?php

require(__DIR__.'/../vendor/autoload.php');

use PatryQHyper\Payments\DirectBilling\PaybylinkDirectBilling;

try {
    $payment = new PaybylinkDirectBilling('login', 'password', 'hash');

    $payment->generate('price (float)', 'description', 'control');

    # $payment->getTransactionUrl(); - get transaction url

} catch (\PatryQHyper\Payments\Exceptions\DirectBillingException $exception) {
    echo $exception->getMessage();
}
```