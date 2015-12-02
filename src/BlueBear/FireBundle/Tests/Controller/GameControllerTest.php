<?php

namespace BlueBear\FireBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/fire/run');

        $this->assertTrue($crawler->filter('table > tr > td')->count() > 0);
    }
}
