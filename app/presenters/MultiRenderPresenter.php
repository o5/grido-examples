<?php

use Nette\Utils\Html,
    Grido\Components\Filters\Filter;

final class MultiRenderPresenter extends BasePresenter
{
    /**
     * Only columns and operations.
     * @param string $name
     * @return \Grido\Grid
     */
    private function baseGrid($name)
    {
        $grid = new Grido\Grid($this, $name);
        $grid->defaultPerPage = 4;

        $fluent = $this->context->dibi_sqlite->select('u.*, c.title AS country')
            ->from('[user] u')
            ->join('[country] c')->on('u.country_code = c.code');
        $grid->setModel($fluent);

        $grid->addColumnText('firstname', 'Firstname')
            ->setSortable()
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('firstname')->cellPrototype->class[] = 'center';

        $grid->addColumnText('surname', 'Surname')
            ->setSortable()
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('surname')->cellPrototype->class[] = 'center';

        $grid->addColumnText('gender', 'Gender')
            ->setSortable()
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('gender')->cellPrototype->class[] = 'center';

        $grid->addColumnDate('birthday', 'Birthday', Grido\Components\Columns\Date::FORMAT_TEXT)
            ->setSortable()
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('birthday')->cellPrototype->class[] = 'center';

        $baseUri = $this->template->baseUri;
        $grid->addColumnText('country', 'Country')
            ->setSortable()
            ->setCustomRender(function($item) use($baseUri) {
                $img = Html::el('img')->src("$baseUri/img/flags/$item->country_code.gif");
                return "$img $item->country";
            })->headerPrototype->class[] = 'center';
        $grid->getColumn('country')->cellPrototype->class[] = 'center';

        $grid->addColumn('city', 'City')
            ->setSortable()
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('city')->cellPrototype->class[] = 'center';

        $grid->addColumn('zip', 'ZIP')
            ->setColumn('zipcode')
            ->setSortable()
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('zip')->cellPrototype->class[] = 'center';

        $grid->addColumn('phone', 'Phone')
            ->setColumn('telephonenumber')
            ->setSortable()
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('phone')->cellPrototype->class[] = 'center';

        $grid->addColumnMail('email', 'Email')
            ->setColumn('emailaddress')
            ->setSortable()
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('email')->cellPrototype->class[] = 'center';


        $grid->addColumnText('card', 'Card')
            ->setSortable()
            ->setColumn('cctype') //name of db column
            ->setReplacement(array('MasterCard' => Html::el('b')->setText('MasterCard')))
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('card')->cellPrototype->class[] = 'center';

        $grid->addColumnText('height', 'Height')
            ->setColumn('centimeters')
            ->setSortable()
            ->headerPrototype->class[] = 'center';
        $grid->getColumn('height')->cellPrototype->class[] = 'center';

        $operations = array('print' => 'Print', 'delete' => 'Delete');
        $grid->setOperations($operations, callback($this, 'gridOperationsHandler'))
            ->setConfirm('delete', 'Are you sure you want to delete %i items?');

        $grid->setExporting();

        return $grid;
    }

    /**
     * Adds actions.
     * @param Grido\Grid $grid
     * @return \Grido\Grid
     */
    private function addActions(Grido\Grid $grid)
    {
        $grid->addActionHref('edit', 'Edit')
            ->setIcon('pencil');

        $grid->addActionHref('delete', 'Delete')
            ->setIcon('trash')
            ->setConfirm(function($item) {
                return "Are you sure you want to delete {$item->firstname} {$item->surname}?";
        });

        return $grid;
    }

    /**
     * Adds filters.
     * @param Grido\Grid $grid
     * @return \Grido\Grid
     */
    private function addFilters(Grido\Grid $grid)
    {
        $grid->getColumn('firstname')->setFilterText()
            ->setSuggestion();

        $grid->getColumn('surname')->setFilterText()
            ->setSuggestion();

        $grid->getColumn('gender')->setFilterSelect(array(
            '' => '',
            'female' => 'female',
            'male' => 'male'
        ));
        $grid->getColumn('birthday')->setFilterDate();

        $grid->getColumn('country')->setFilterText()
            ->setSuggestion();

        $grid->getColumn('city')->setFilterText()
            ->setSuggestion();

        $grid->getColumn('zip')->setFilterText()
            ->setSuggestion();

        $grid->getColumn('phone')->setFilterText()
            ->setSuggestion();

        $grid->getColumn('email')->setFilterText()
            ->setSuggestion();

        $grid->getColumn('card')->setFilterSelect(array(
            '' => '',
            'MasterCard' => 'MasterCard',
            'Visa' => 'Visa'
        ));

        $grid->getColumn('height')->setFilterNumber()
            ->setSuggestion();

        return $grid;
    }

    protected function createComponentOne($name)
    {
        $grid = $this->baseGrid($name);
        $grid->filterRenderType = Filter::RENDER_INNER;
    }

    protected function createComponentTwo($name)
    {
        $grid = $this->baseGrid($name);
        $grid->filterRenderType = Filter::RENDER_INNER;

        $this->addActions($grid);
    }

    protected function createComponentThree($name)
    {
        $grid = $this->baseGrid($name);
        $grid->filterRenderType = Filter::RENDER_INNER;

        $this->addFilters($grid);
    }

    protected function createComponentFour($name)
    {
        $grid = $this->baseGrid($name);
        $grid->filterRenderType = Filter::RENDER_INNER;

        $this->addActions($grid);
        $this->addFilters($grid);
    }

    protected function createComponentFive($name)
    {
        $grid = $this->baseGrid($name);
        $grid->filterRenderType = Filter::RENDER_OUTER;
    }

    protected function createComponentSix($name)
    {
        $grid = $this->baseGrid($name);
        $grid->filterRenderType = Filter::RENDER_OUTER;

        $this->addActions($grid);
    }

    protected function createComponentSeven($name)
    {
        $grid = $this->baseGrid($name);
        $grid->filterRenderType = Filter::RENDER_OUTER;

        $this->addFilters($grid);
    }

    protected function createComponentEight($name)
    {
        $grid = $this->baseGrid($name);
        $grid->filterRenderType = Filter::RENDER_OUTER;

        $this->addActions($grid);
        $this->addFilters($grid);
    }

    public function renderDefault()
    {
        $this->template->list = array();
        $methods = $this->getReflection()->getMethods(ReflectionMethod::IS_PROTECTED);
        foreach ($methods as $method) {
            if ($method->class != __CLASS__) {
                break;
            }

            $grid = strtolower(str_replace('createComponent', '', $method->name));
            $this->template->list[] = $grid;

            $this[$grid]; //WORKAROUND! A better visualization of the error 500..
        }
    }
}
