<?php
/**
 * Created by PhpStorm.
 * User: ersah
 * Date: 4/7/17
 * Time: 11:01 AM
 */

namespace Ersah\GABundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class GAAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'ga';
    protected $baseRoutePattern = 'ga';

    public function configure() {
        $this->setTemplate('list', 'ErsahGABundle:GA:GAList.html.twig');
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('delete')
            ->remove('create')
            ->remove('edit')
            ->remove('show');
    }
}