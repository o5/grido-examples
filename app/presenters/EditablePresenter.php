<?php

use Nette\Utils\Html;

/**
 * Editable example.
 * @link http://doc.nette.org/en/database
 *
 * @package     Grido
 * @author      Petr Bugyík
 */
final class EditablePresenter extends BasePresenter
{
    /** @var Nette\Database\Context @inject */
    public $database;

    protected function createComponentGrid($name)
    {
        $grid = new Grido\Grid($this, $name);
        $grid->model = $this->database->table('user');

        $grid->setEditableColumns();

        $grid->addColumnText('firstname', 'Firstname')
            ->setFilterText()
                ->setSuggestion();

        $grid->addColumnText('surname', 'Surname')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();

        $grid->addColumnText('gender', 'Gender')
            ->setEditableControl(new Nette\Forms\Controls\SelectBox(NULL, ['male' => 'Male', 'female' => 'Female']))
            ->setEditableCallback(function() {dump("kks");exit(1);})
            ->setEditableValueCallback(function($row) {return $row->gender . 'HEJJJ';})
            ->setSortable()
            ->cellPrototype->class[] = 'center';

        $grid->getColumn('gender')->getEditableControl()->setRequired('HEEEJ');

        $grid->addColumnDate('birthday', 'Birthday', Grido\Components\Columns\Date::FORMAT_TEXT)
            ->setSortable()
            ->setFilterDate()
                ->setCondition($this->gridBirthdayFilterCondition);
        $grid->getColumn('birthday')->cellPrototype->class[] = 'center';

        $templatePath = "{$this->context->parameters['appDir']}/templates/{$this->name}";
        $grid->addColumnText('country', 'Country')
            ->disableEditable()
            ->setSortable()
            ->setCustomRender("$templatePath/grid.country.latte")
            ->setCustomRenderExport($renderer)
            ->setFilterText()
                ->setSuggestion(function($row) { return $row->country->title; });

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

        $grid->addActionEvent('remove', 'Remove', function($id, $action) {
            $this->context->ndb_sqlite->table('user')->where('id = ?', $id)->delete();
            $action->grid->reload();
        })  ->setConfirm('Are you sure?')
           ->elementPrototype->class[] = 'ajax';

        $operation = array('print' => 'Print', 'delete' => 'Delete');
        $grid->setOperation($operation, $this->gridOperationsHandler)
            ->setConfirm('delete', 'Are you sure you want to delete %i items?');

        $grid->filterRenderType = $this->filterRenderType;
        $grid->setExport();
    }
}
