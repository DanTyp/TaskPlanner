<?php

namespace TaskPlannerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AplicationControllerTest extends WebTestCase
{
    public function testWelcome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

}
