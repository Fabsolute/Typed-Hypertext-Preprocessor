<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 09/07/2017
 * Time: 15:14
 */

namespace THP\Parser;


class Operator
{
    public $operator_type = 0;
    public $priority = 0;

    public function __construct($operator_type, $priority)
    {
        $this->operator_type = $operator_type;
        $this->priority = $priority;
    }
}