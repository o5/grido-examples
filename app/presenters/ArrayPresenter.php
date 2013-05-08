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

        $grid->addColumnDate('last_login', 'Poslední přihlášení')
            ->setDateFormat(\Grido\Components\Columns\Date::FORMAT_DATETIME)
            ->setReplacement(array(NULL => 'Nikdy'));
        $grid->getColumn('last_login')->cellPrototype->class[] = 'center';

        $grid->addActionHref('edit', 'Upravit')
            ->setIcon('pencil')
            ->setCustomRender(callback($this, 'gridHrefRender'));

        $grid->addActionHref('delete', 'Smazat')
            ->setIcon('trash')
            ->setCustomRender(callback($this, 'gridHrefRender'))
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
     * Grid callback.
     * @param array $item
     * @param Nette\Utils\Html $el
     * @return \Nette\Utils\Html
     */
    public function gridHrefRender(array $item, Nette\Utils\Html $el)
    {
        if ($item['last_login'] === NULL) {
            $el->class[] = 'btn-danger';
        }

        return $el;
    }

    /**
     * @return array
     */
    private function getData()
    {
        $data = array(
            array('id' => 1,  'firstname' => 'Eva',     'surname' => 'Malá'),
            array('id' => 2,  'firstname' => 'Adéla',   'surname' => 'Střední'),
            array('id' => 3,  'firstname' => 'Jana',    'surname' => 'Absolonová'),
            array('id' => 4,  'firstname' => 'Lucie',   'surname' => 'Šikovná'),
            array('id' => 5,  'firstname' => 'Andrea',  'surname' => 'Potřebná'),
            array('id' => 6,  'firstname' => 'Michala', 'surname' => 'Zadní'),
            array('id' => 7,  'firstname' => 'Markéta', 'surname' => 'Mladá'),
            array('id' => 8,  'firstname' => 'Lenka',   'surname' => 'Přední'),
            array('id' => 9,  'firstname' => 'Marie',   'surname' => 'Dolní'),
            array('id' => 10, 'firstname' => 'Hanka',   'surname' => 'Horní'),
            array('id' => 11, 'firstname' => 'Petra',   'surname' => 'Vysoká'),
        );

        $limit = array(1, 9);
        foreach ($data as &$item) {

            $d = rand($limit[0], $limit[1]);
            $h = rand($limit[0], $limit[1]);
            $m = rand($limit[0], $limit[1]);
            $s = rand($limit[0], $limit[1]);

            $item['last_login'] = $item['id'] == 4
                ? NULL
                : new DateTime("NOW - {$d}day {$h}hour {$m}minute {$s}second");
        }

        return $data;
    }
}
