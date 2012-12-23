<?php

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    public function createTemplate($class = NULL)
    {
        $template = parent::createTemplate($class);
        $template->registerHelper('strtoupper', 'strtoupper');

        $baseUri = $this->context->parameters['baseUri'];
        $template->baseUri = $baseUri ? $baseUri : $template->basePath;;

        return $template;
    }
}
