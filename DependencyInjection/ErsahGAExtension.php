<?php

namespace Ersah\GABundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ErsahGAExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('view_id', $config['view_id']);
        $container->setParameter('json_key_file', $config['google_analytics_json_key']);
        $container->setParameter('limit', $config['limit']);
        $container->setParameter('from', $config['from']);
        $container->setParameter('to', $config['to']);
        $container->setParameter('dimensions', $config['list']['dimensions']);
        $container->setParameter('listMetrics', $config['list']['metrics']);
        //dump($config);exit;
        if(array_key_exists('main', $config)){
            $container->setParameter('mainMetrics', $config['main']['metrics']);
        } else {
            $container->setParameter('mainMetrics', null);
        }

        $container->setParameter('sortBy', $config['sortBy']);
        $container->setParameter('sorting', $config['sorting']);
    }
}
