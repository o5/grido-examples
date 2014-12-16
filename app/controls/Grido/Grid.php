<?php

namespace App\Controls\Grido;

/**
 * Base of grid.
 */
class Grid extends \Grido\Grid
{
    /**
     * Custom condition callback for filter birthday.
     * @param string $value
     * @return array|NULL
     */
    public function birthdayFilterCondition($value)
    {
        $date = explode('.', $value);
        foreach ($date as &$val) {
            $val = (int) $val;
        }

        return count($date) == 3
            ? array('birthday', '= ?', "{$date[2]}-{$date[1]}-{$date[0]}")
            : NULL;
    }

    /**
     * @param string $name
     * @param string $label
     * @return Components\Columns\Boolean
     */
    public function addColumnBoolean($name, $label)
    {
        $column = new Components\Columns\Boolean($this, $name, $label);

        $header = $column->headerPrototype;
        $header->style['width'] ='2%';
        $header->class[] = 'center';

        return $column;
    }
}
