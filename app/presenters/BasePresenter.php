<?php

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    public $baseUri;

    public function createTemplate($class = NULL)
    {
        $template = parent::createTemplate($class);
        $template->registerHelper('strtoupper', 'strtoupper');

        $uri = Nette\Environment::getVariable('baseUri', NULL);
        $this->baseUri = $template->baseUri = $uri ? $uri : $template->basePath;

        return $template;
    }
}
