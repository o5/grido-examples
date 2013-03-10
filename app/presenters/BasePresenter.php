<?php

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    public function createTemplate($class = NULL)
    {
        $template = parent::createTemplate($class);

        $baseUri = $this->context->parameters['baseUri'];
        $template->baseUri = $baseUri ? $baseUri : $template->basePath;;

        $latte = new Nette\Latte\Engine;
        $macros = Nette\Latte\Macros\MacroSet::install($latte->compiler);
        $macros->addMacro('scache', '?>?<?php echo strtotime(date(\'Y-m-d hh \')); ?>"<?php');

        $template->registerFilter($latte);

        return $template;
    }
}
