<?php

class AjaxPresenter extends Nette\Application\UI\Presenter
{
    public function actionSourceCode()
    {
        if (!$this->isAjax()) {
            die('Ajax only :)');
        }

        $this->payload->snippets['source-code'] = highlight_file(
            $this->context->params['appDir'].'/presenters/ExamplePresenter.php', TRUE
        );
        $this->terminate();
    }
}
