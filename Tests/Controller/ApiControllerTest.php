<?php
/**
 * Created by PhpStorm.
 * User: ersah
 * Date: 4/24/17
 * Time: 3:27 PM
 */

namespace Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testAdminList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/ga/report.json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
