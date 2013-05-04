<?php

/**
 * Array example.
 *
 * @package     Grido
 * @author      Petr Bugyík
 */
final class ArrayPresenter extends BasePresenter
{
    protected function createComponentGrid($name)
    {
        $grid = new Grido\Grid($this, $name);

        $grid->translator->lang = 'cs';
        $grid->defaultPerPage = 5;

        $grid->setModel($this->getData());

        $grid->addColumnText('firstname', 'Jméno')
            ->setFilterText()
                ->setSuggestion();
        $grid->getColumn('firstname')->headerPrototype->style = 'width: 30%';

        $grid->addColumnText('surname', 'Příjmení')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        $grid->getColumn('surname')->headerPrototype->style = 'width: 30%';

        $grid->addColumn('card', 'Platební karta')
            ->setSortable()
            ->setReplacement(array('Visa' => Nette\Utils\Html::el('b')->setText('Visa')))
            ->setFilterSelect(array(
                '' => '',
                'Visa' => 'Visa',
                'MasterCard' => 'MasterCard'
            ));
        $grid->getColumn('card')->cellPrototype->class[] = 'center';

        $grid->addActionHref('edit', 'Upravit')
            ->setIcon('pencil');

        $grid->addActionHref('delete', 'Smazat')
            ->setIcon('trash')
            ->setConfirm(function($item) {
                return "Opravdu chcete smazat slečnu se jménem {$item['firstname']} {$item['surname']}?";
        });

        $operations = array('print' => 'Print', 'delete' => 'Delete');
        $grid->setOperations($operations, callback($this, 'gridOperationsHandler'))
            ->setConfirm('delete', 'Opravdu chcete smazat označené položky? (%i)');

        $grid->setFilterRenderType($this->filterRenderType);
        $grid->setExporting();
    }

    /**
     * @return array
     */
    private function getData()
    {
        return array(
            array('id' => 1, 'firstname' => 'Eva', 'surname' => 'Malá', 'card' => 'Visa'),
            array('id' => 2, 'firstname' => 'Adéla', 'surname' => 'Střední', 'card' => 'MasterCard'),
            array('id' => 3, 'firstname' => 'Jana', 'surname' => 'Absolonová', 'card' => 'Visa'),
            array('id' => 4, 'firstname' => 'Andrea', 'surname' => 'Potřebná', 'card' => 'Visa'),
            array('id' => 5, 'firstname' => 'Lucie', 'surname' => 'Šikovná', 'card' => 'MasterCard'),
            array('id' => 6, 'firstname' => 'Michala', 'surname' => 'Zadní', 'card' => 'Visa'),
            array('id' => 7, 'firstname' => 'Markéta', 'surname' => 'Mladá', 'card' => 'MasterCard'),
            array('id' => 8, 'firstname' => 'Lenka', 'surname' => 'Přední', 'card' => 'MasterCard'),
            array('id' => 9, 'firstname' => 'Marie', 'surname' => 'Dolní', 'card' => 'Visa'),
            array('id' => 10, 'firstname' => 'Hanka', 'surname' => 'Horní', 'card' => 'Visa'),
            array('id' => 11, 'firstname' => 'Petra', 'surname' => 'Vysoká', 'card' => 'Visa'),
        );
    }
}
