<?php

use unapi\avtocod\AvtocodDevService;

class AvtocodDevServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testPing()
    {
        $service = new AvtocodDevService();

        $value = uniqid();
        $response = $service->ping($value)->wait();

        $this->assertArrayHasKey('value', $response);
        $this->assertEquals($value, $response['value']);
    }

    public function testToken()
    {
        $service = new AvtocodDevService();

        $response = $service->token('test@test', '123', false, date('Y-m-d\TH:i:s\Z'))->wait();

        $this->assertArrayHasKey('token', $response);
    }
}