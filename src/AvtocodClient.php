<?php

namespace unapi\avtocod;

use GuzzleHttp\Client;

class AvtocodClient extends Client
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['base_uri'] = 'https://b2bapi.avtocod.ru';

        parent::__construct($config);
    }
}