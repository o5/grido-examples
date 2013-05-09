<?php

namespace Grido\Components\Columns;

/**
 * Boolean column.
 *
 * @author      Pavel Kryštůfek (http://www.krystufkovi.cz)
 */
class Boolean extends Text
{
    public function getCellPrototype()
    {
        $cell = parent::getCellPrototype();
        $cell->class[] = 'center';

        return $cell;
    }

    /**
     * @param $value
     * @return \Nette\Utils\Html
     */
    protected function formatValue($value)
    {
        if ($value == 0) {
            $a = \Nette\Utils\Html::el('i')->class("icon-remove");
        } else {
            $a = \Nette\Utils\Html::el('i')->class("icon-ok");
        }

        return $a;
    }
}
