<?php

namespace App\Presenters;

use Grido\Grid;
use Nette\Utils\Html;

/**
 * Editable example.
 *
 * @package     Grido
 * @author      Petr Bugyík
 */
final class EditablePresenter extends Presenter
{
    /** @var \Nette\Database\Context @inject */
    public $database;

    protected function createComponentGrid($name)
    {
        $grid = new Grid($this, $name);
        $grid->model = $this->database->table('user');

        $grid->setEditableColumns(function($id, $newValue, $oldValue, $column) {
            //do update ... and return result
            return TRUE;
        });

        $grid->addColumnText('firstname', 'Firstname')
            ->setFilterText()
                ->setSuggestion();

        $grid->addColumnText('surname', 'Surname')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        $grid->getColumn('surname')->getEditableControl()->setRequired('Surname is required.');

        $genderList = array('female' => 'female', 'male' => 'male');
        $grid->addColumnText('gender', 'Gender')
            ->setEditableControl(new \Nette\Forms\Controls\SelectBox(NULL, $genderList))
            ->setSortable()
            ->cellPrototype->class[] = 'center';

        $grid->addColumnDate('birthday', 'Birthday', \Grido\Components\Columns\Date::FORMAT_TEXT)
            ->setSortable()
            ->setFilterDate()
                ->setCondition($this->gridBirthdayFilterCondition);

        $grid->getColumn('birthday')->cellPrototype->class[] = 'center';

        $grid->getColumn('birthday')->getEditableControl()->controlPrototype->class[] = 'date';
        $grid->getColumn('birthday')->setEditableValueCallback(function($row, $column) {
            return date($column::FORMAT_DATE, strtotime($row->birthday));
        });

        $cardList = array('MasterCard' => 'MasterCard', 'Visa' => 'Visa');
        $grid->addColumnText('card', 'Card')
            ->setSortable()
            ->setColumn('cctype') //name of db column
            ->setReplacement(array('MasterCard' => Html::el('b')->setText('MasterCard')))
            ->setEditableControl(new \Nette\Forms\Controls\SelectBox(NULL, $cardList))
            ->cellPrototype->class[] = 'center';

        $grid->addColumnEmail('emailaddress', 'Email')
            ->setSortable()
            ->setFilterText();
        $grid->getColumn('emailaddress')->cellPrototype->class[] = 'center';

        $grid->addColumnText('centimeters', 'Height')
            ->setSortable()
            ->setFilterNumber();
        $grid->getColumn('centimeters')->cellPrototype->class[] = 'center';
        $grid->getColumn('centimeters')->getEditableControl()->controlPrototype->type = 'number';

        $grid->addFilterSelect('gender', 'Gender', array('' => '') + $genderList);

        $grid->addFilterSelect('card', 'Card', array('' => '') + $cardList)
            ->setColumn('cctype');

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
