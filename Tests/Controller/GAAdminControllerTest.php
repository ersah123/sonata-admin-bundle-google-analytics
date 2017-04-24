<?php

namespace Ersah\GABundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GAAdminControllerTest extends WebTestCase
{
    public function testAdminList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/ga/list');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
