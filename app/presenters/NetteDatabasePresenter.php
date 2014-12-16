<?php

namespace App\Presenters;

use Grido\Grid;
use Nette\Utils\Html;

/**
 * Nette\Database example.
 * @link http://doc.nette.org/en/database
 *
 * @package     Grido
 * @author      Petr Bugyík
 */
final class NetteDatabasePresenter extends BasePresenter
{
    /** @var \Nette\Database\Context @inject */
    public $database;

    protected function createComponentGrid($name)
    {
        $grid = new Grid($this, $name);
        $grid->model = $this->database->table('user');

        $grid->addColumnText('firstname', 'Firstname')
            ->setFilterText()
                ->setSuggestion();

        $grid->addColumnText('surname', 'Surname')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();

        $grid->addColumnText('gender', 'Gender')
            ->setSortable()
            ->cellPrototype->class[] = 'center';

        $grid->addColumnDate('birthday', 'Birthday', \Grido\Components\Columns\Date::FORMAT_TEXT)
            ->setSortable()
            ->setFilterDate()
                ->setCondition($this->gridBirthdayFilterCondition);
        $grid->getColumn('birthday')->cellPrototype->class[] = 'center';

        $templatePath = "{$this->context->parameters['appDir']}/templates/{$this->name}";
        $renderer = function($row) { return $row->country->title; };
        $grid->addColumnText('country', 'Country')
            ->setSortable()
            ->setColumn('country.title') //for ordering/filtering
            ->setCustomRender("$templatePath/grid.country.latte")
            ->setCustomRenderExport($renderer)
            ->setFilterText()
                ->setSuggestion($renderer);

        $grid->addColumnText('card', 'Card')
            ->setSortable()
            ->setColumn('cctype') //name of db column
            ->setReplacement(array('MasterCard' => Html::el('b')->setText('MasterCard')))
            ->cellPrototype->class[] = 'center';

        $grid->addColumnEmail('emailaddress', 'Email')
            ->setSortable()
            ->setFilterText();
        $grid->getColumn('emailaddress')->cellPrototype->class[] = 'center';

        $grid->addColumnText('centimeters', 'Height')
            ->setSortable()
            ->setFilterNumber();
        $grid->getColumn('centimeters')->cellPrototype->class[] = 'center';

        $grid->addFilterSelect('gender', 'Gender', array(
            '' => '',
            'female' => 'female',
            'male' => 'male'
        ));

        $grid->addFilterSelect('card', 'Card', array(
                '' => '',
                'MasterCard' => 'MasterCard',
                'Visa' => 'Visa'
            ))
            ->setColumn('cctype');

        $grid->addFilterCheck('preferred', 'Only preferred girls :)')
            ->setCondition(array(
                TRUE => array(array('gender', 'AND', 'centimeters'), array('= ?', '>= ?'), array('female', 170)))
        );

        $grid->addActionHref('edit', 'Edit')
            ->setIcon('pencil');

        $grid->addActionHref('delete', 'Delete')
            ->setIcon('trash')
            ->setConfirm(function($item) {
                return "Are you sure you want to delete {$item->firstname} {$item->surname}?";
        });

        $operation = array('print' => 'Print', 'delete' => 'Delete');
        $grid->setOperation($operation, $this->gridOperationsHandler)
            ->setConfirm('delete', 'Are you sure you want to delete %i items?');

        $grid->filterRenderType = $this->filterRenderType;
        $grid->setExport();
    }
}
