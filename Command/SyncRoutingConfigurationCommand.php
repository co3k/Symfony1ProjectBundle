<?php

namespace Bundle\Symfony1ProjectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Yaml\Yaml;

class SyncRoutingConfigurationCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('symfony1:sync-routing')
      ->setDescription('*WIP*')
      ->addArgument('file', InputArgument::REQUIRED, 'The path to symfony 1 routing.yml')
      ->addArgument('bundle', InputArgument::REQUIRED, '')
      ->addArgument('controller', InputArgument::REQUIRED, '')
      ->setHelp('*WIP*');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $from = Yaml::load($input->getArgument('file'));
    $to = array();

    foreach ($from as $key => $rule)
    {
      if (!isset($rule['url']) || !isset($rule['param']))
      {
        continue;
      }

      $to[$key] = array(
        'pattern'      => $rule['url'],
        'defaults'     => $rule['param'],
      );

      if (isset($rule['requirements']))
      {
        $to[$key]['requirements'] = $rule['requirements'];
      }

      if (isset($rule['param']['action']))
      {
        $to[$key]['defaults']['_controller'] = $input->getArgument('bundle').':'.$input->getArgument('controller').':'.$rule['param']['action'];
      }
    }

    $result = Yaml::dump($to);
    $result = str_replace('sf_', '_', $result);
    $output->writeln($result);
  }
}
