<?php

namespace BlueBear\FireBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testRun()
    {
        // create web client
        $client = static::createClient();
        $crawler = $client->request('GET', '/fire/run');

        // map must be created with cells into rows
        $this->assertTrue($crawler->filter('table > tr > td')->count() > 0);

        // map must have a fireman
        $this->assertTrue($crawler->filter('td.fireman')->count() > 0);

        // map must have at least one fire
        $this->assertTrue($crawler->filter('td.fire')->count() > 0);
    }
}
