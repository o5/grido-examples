<?php

use Grido\Components\Filters\Filter;

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
        $grid->filterRenderType = Filter::RENDER_OUTER;
        $grid->translator->lang = 'cs';
        $grid->defaultPerPage = 5;

        $grid->setModel($this->getData());

        $grid->addColumnNumber('id', '#')
            ->cellPrototype->class[] = 'center';
        $header = $grid->getColumn('id')->headerPrototype;
        $header->rowspan = "2";
        $header->style['width'] = '0.1%';

        $grid->addColumnText('firstname', 'Jméno')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        $grid->getColumn('firstname')->headerPrototype->style['width'] = '10%';

        $grid->addColumnText('surname', 'Příjmení')
            ->setSortable()
            ->setFilterText()
                ->setSuggestion();
        $grid->getColumn('surname')->headerPrototype->style['width'] = '10%';

        $grid->addColumnNumber('allowance', 'Kapesné [CZK]', 2, ',', ' ')
            ->setSortable()
            ->setFilterNumber();
        $grid->getColumn('allowance')->cellPrototype->class[] = 'center';
        $grid->getColumn('allowance')->headerPrototype->class[] = 'center';
        $grid->getColumn('allowance')->headerPrototype->style['width'] = '6%';

        $grid->addFilterCustom('name', new \Nette\Forms\Controls\TextArea('Jméno nebo příjmení'))
            ->setColumn('firstname')
            ->setColumn('surname', Filter::OPERATOR_OR)
            ->setCondition('LIKE %s')
            ->setFormatValue('%%value%');

        $grid->addColumnDate('last_login', 'Poslední přihlášení')
            ->setSortable()
            ->setDateFormat(\Grido\Components\Columns\Date::FORMAT_DATETIME)
            ->setReplacement(array(NULL => 'Nikdy'));
        $grid->getColumn('last_login')->cellPrototype->class[] = 'center';
        $grid->getColumn('last_login')->headerPrototype->class[] = 'center';
        $grid->getColumn('last_login')->headerPrototype->style['width'] = '9%';

        $column = new Grido\Components\Columns\Boolean($grid, 'ok', 'OK');
        $column->setSortable()
            ->headerPrototype->style['width'] ='2%';
        $grid->getColumn('ok')->headerPrototype->class[] = 'center';

        $grid->addActionHref('edit', 'Upravit')
            ->setIcon('pencil')
            ->setCustomRender(callback($this, 'gridHrefRender'));

        $grid->addActionHref('delete', 'Smazat')
            ->setIcon('trash')
            ->setCustomRender(callback($this, 'gridHrefRender'))
            ->setConfirm(function($item) {
                return "Opravdu chcete smazat slečnu se jménem {$item['firstname']} {$item['surname']}?";
        });

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
     * Returns "generated" data.
     * NOTE: This location is only for demo!
     * @return array
     */
    private function getData($cacheKey = 'data')
    {
        $storage = new Nette\Caching\Storages\FileStorage($this->context->parameters['tempDir']);
        $cache = new Nette\Caching\Cache($storage, 'example_data');
        $data = $cache->load($cacheKey);

        if (empty($data)) {
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
                    : new Nette\DateTime("NOW - {$d}day {$h}hour {$m}minute {$s}second");

                $item['allowance'] = rand(10000, 100000) / 10;
                $item['ok'] = (bool) rand(0, 1);
            }

            $cache->save($cacheKey, $data, array(
                Nette\Caching\Cache::EXPIRE => '+ 60 minutes',
            ));
        }

        return $data;
    }
}
