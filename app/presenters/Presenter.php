<?php

namespace App\Presenters;

abstract class Presenter extends \Nette\Application\UI\Presenter
{
    /** @var string @persistent */
    public $ajax = 'on';

    /** @var string @persistent */
    public $filterRenderType = \Grido\Components\Filters\Filter::RENDER_INNER;

    public function handleCloseTip()
    {
        $this->context->httpResponse->setCookie('grido-examples-first', 0, 0);
        $this->redirect('this');
    }

    /**
     * Common handler for grid operations.
     * @param string $operation
     * @param array $id
     */
    public function handleOperations($operation, $id)
    {
        if ($id) {
            $row = implode(', ', $id);
            $this->flashMessage("Process operation '$operation' for row with id: $row...", 'info');
        } else {
            $this->flashMessage('No rows selected.', 'error');
        }

        $this->redirect($operation, array('id' => $id));
    }

    public function actionEdit($id)
    {
        $this->flashMessage("Action '$this->action' for row with id: $id done.", 'success');
        $this->redirect('default');
    }

    public function actionDelete()
    {
        $id = $this->getParameter('id');
        $id = is_array($id) ? implode(', ', $id) : $id;
        $this->flashMessage("Action '$this->action' for row with id: $id done.", 'success');
        $this->redirect('default');
    }

    public function actionPrint()
    {
        $id = $this->getParameter('id');
        $id = is_array($id) ? implode(', ', $id) : $id;
        $this->flashMessage("Action '$this->action' for row with id: $id done.", 'success');
        $this->redirect('default');
    }

    public function renderDefault()
    {
        $this['grid']; //WORKAROUND! A better visualization of the error 500..
    }

    /**********************************************************************************************/

    protected function createTemplate($class = NULL)
    {
        $template = parent::createTemplate();
        $latte = $template->getLatte();

        $set = new \Latte\Macros\MacroSet($latte->getCompiler());
        $set->addMacro('scache', '?>?<?php echo strtotime(date(\'Y-m-d hh \')); ?>"<?php');

        $latte->addFilter('scache', $set);
        return $template;
    }

    public function beforeRender()
    {
        $baseUri = \App\Routers\RouterFactory::getExtraPath();
        $this->template->baseUri = $baseUri ? $baseUri : $this->template->basePath;
        $this->template->first = $this->context->httpRequest->getCookie('grido-examples-first', 1);
    }
}
