<?php

namespace Utils;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GATest extends WebTestCase
{
    public function getContainer()
    {
        return static::createClient()->getContainer();
    }

    public function testSearch()
    {
        $report = $this->getContainer()->get('sonata.admin.service.ga')
            ->getReport(0,null,null,null, null,null);
        $searchParam = [$report['labels'][0]['value']=>'test']; // get one of the column and put search param

        $searchReport = $this->getContainer()->get('sonata.admin.service.ga')
            ->getReport(0,null,null,null, null, $searchParam);
        $this->assertEquals(200, $searchReport['status']);
    }
}