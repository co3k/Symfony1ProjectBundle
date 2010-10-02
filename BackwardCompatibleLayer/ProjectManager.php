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

    $configuration = \ProjectConfiguration::getApplicationConfiguration($this->application, 'prod', false);
    \sfContext::createInstance($configuration);
  }

  public function executeAction($module, $action)
  {
    restore_error_handler();

    // This is a trick not to output in symfony 1
    $response = \sfContext::getInstance()->getResponse();
    $response->setHeaderOnly(true);

    try
    {
      \sfContext::getInstance()->getController()->forward($module, $action);
    }
    catch (\sfStopException $e)
    {
      exit;
    }

    return new Response($response->getContent());
  }
}
