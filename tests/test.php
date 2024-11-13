<?php

use PHPUnit\Framework\TestCase;
use DimplesPay\DimplesPayClient;

class DimplesPayClientTest extends TestCase
{
    public function testAuthenticate()
    {
        $client = new DimplesPayClient('https://dimplespay.com/pay/sandbox/api/v1', 'test_client_id', 'test_secret_id');
        $this->expectException(\DimplesPay\AuthenticationException::class);
        $client->authenticate();
    }
}
