<?php

namespace Bundle\Symfony1ProjectBundle\BackwardCompatibleLayer;

class Request
{
  protected
    $request = null,
    $bcRequest = null;

  public function __construct($request)
  {
    $this->request = $request;
    $this->bcRequest = new \sfWebRequest(new sfEventDispatcher());
  }
}
