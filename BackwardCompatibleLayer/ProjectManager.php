<?php

namespace Bundle\Symfony1ProjectBundle\BackwardCompatibleLayer;

use Symfony\Component\HttpFoundation\Response;

class ProjectManager
{
  protected
    $request = null,

    $pathToProject = '',
    $application = '';

  public function __construct($request, $projectName, $application)
  {
    $this->request = $request;
    $this->pathToProject = __DIR__.'/../../../vendor/'.$projectName;
    $this->application = $application;

    $configFile = $this->pathToProject.'/config/ProjectConfiguration.class.php';
    if (!is_file($configFile))
    {
      throw new \RuntimeException('You need to set your symfony 1 project in the vendor direcotry. If you set up correctly, the ProjectConfiguration class file will be located at '.$configFile);
    }

    require_once $configFile;

    $configuration = \ProjectConfiguration::getApplicationConfiguration($this->application, 'dev', true);
    \sfContext::createInstance($configuration);
  }

  public function executeAction($module, $action)
  {
    restore_error_handler();

    try
    {
      ob_start();
      \sfContext::getInstance()->getController()->forward($module, $action);
      $result = ob_get_flush();
    }
    catch (\sfStopException $e)
    {
      exit;
    }

    return new Response('');
  }
}
