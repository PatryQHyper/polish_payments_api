<?php

namespace PatryQHyper\Payments\Transfer;

use PatryQHyper\Payments\WebClient;

class MicrosmsTransfer
{
    private int $user_id;
    private int $shop_id;
    private string $hash;
    private bool $use_md5;

    private string $redirect_url = 'https://microsms.pl/api/bankTransfer';

    private array $params = [];

    public function __construct(int $user_id, int $shop_id, string $hash, bool $use_md5 = false)
    {
        $this->user_id = $user_id;
        $this->shop_id = $shop_id;
        $this->hash = $hash;
        $this->use_md5 = $use_md5;
    }

    public function generate(
        float $amount,
        ?string $control=NULL,
        ?string $return_urlc=NULL,
        ?string $return_url=NULL,
        ?string $description=NULL
    )
    {
        $data['shopid'] = $this->shop_id;
        $data['amount'] = $amount;
        if(!is_null($control)) $data['control'] = $control;
        if(!is_null($return_urlc)) $data['return_urlc'] = $return_urlc;
        if(!is_null($return_url)) $data['return_url'] = $return_url;
        if(!is_null($description)) $data['description'] = $description;
        $data['signature'] = $this->use_md5 ? md5($this->shop_id.$this->hash.$amount) : hash('sha256', $this->shop_id.$this->hash.$amount);

        $this->params = $data;
    }

    public function getRedirectUrl(bool $add_parameters=false)
    {
        return $this->redirect_url.($add_parameters ? ('?'.http_build_query($this->params)) : '');
    }

    public function getParameters()
    {
        return $this->params;
    }

    public function checkIp($ip)
    {
        if(!in_array($ip, explode(',', file_get_contents('https://microsms.pl/psc/ips/')))) return false;
        return true;
    }
}