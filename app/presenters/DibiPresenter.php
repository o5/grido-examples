<?php

namespace App\Presenters;

use App\Controls\Grido\Grid;
use Nette\Utils\Html;

/**
 * Dibi example.
 * @link http://dibiphp.com/
 *
 * @package     Grido
 * @author      Petr Bugyík
 */
final class DibiPresenter extends Presenter
{
    /** @var \DibiConnection @inject */
    public $database;

    protected function createComponentGrid($name)
    {
        $grid = new Grid($this, $name);

        $fluent = $this->database->select('u.*, c.title AS country')
            ->from('[user] u')
            ->join('[country] c')->on('u.country_code = c.code');
        $grid->model = $fluent;

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
                ->setCondition($grid->birthdayFilterCondition);
        $grid->getColumn('birthday')->cellPrototype->class[] = 'center';

        $baseUri = $this->template->baseUri;
        $customRender = function($item) use($baseUri) {
            $img = Html::el('img')->src("$baseUri/img/flags/$item->country_code.gif");
            return "$img $item->country";
        };
        $grid->addColumnText('country', 'Country')
            ->setSortable()
            ->setCustomRender($customRender)
            ->setFilterText()
                ->setColumn('c.title')
                ->setSuggestion('title');

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
        $grid->setOperation($operation, $this->handleOperations)
            ->setConfirm('delete', 'Are you sure you want to delete %i items?');

        $grid->filterRenderType = $this->filterRenderType;
        $grid->setExport();

        return $grid;
    }
}
