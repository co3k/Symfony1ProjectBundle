<?php

namespace Bundle\Symfony1ProjectBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Finder\Finder;

class Symfony1ProjectExtension extends Extension
{
  protected $resources = array(
      'symfony1' => 'sf1.xml',
  );

    public function project_managerLoad($config, ContainerBuilder $container)
    {
      if (!$container->hasDefinition('symfony1.project_manager'))
      {
        $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
        $loader->load($this->resources['symfony1']);
      }
    }

    public function configLoad($config)
    {
    }

    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/';
    }

    public function getNamespace()
    {
        return 'http://www.example.com/symfony/schema/symfony1-project';
    }

    public function getAlias()
    {
        return 'symfony1';
    }
}
