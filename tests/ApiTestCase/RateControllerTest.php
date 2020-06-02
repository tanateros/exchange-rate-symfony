<?php

namespace App\Tests\ApiTestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RateControllerTest extends WebTestCase
{
    public function testGoodGetRequest(): void
    {
        $client = static::createClient();

        $client->request('GET', '/rate/get?from=USD&to=EUR&amount=10');
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('rate', $content);
        $this->assertTrue($content['rate'] > 0);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGoodGetRequestWithoutCrossCurrency(): void
    {
        $client = static::createClient();

        $client->request('GET', '/rate/get?from=USD&to=RUB');
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('rate', $content);
        $this->assertTrue($content['rate'] > 0);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testBadGetRequest(): void
    {
        $client = static::createClient();

        $client->request('GET', '/rate/get?to=TEST');
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('rate', $content);
        $this->assertSame($content['rate'], 0);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
