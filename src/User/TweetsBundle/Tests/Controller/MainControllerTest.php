<?php

namespace User\TweetsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testProfile()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profile');
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testMytweets()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/my');
    }

}
