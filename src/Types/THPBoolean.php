<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 09/07/2017
 * Time: 15:38
 */

namespace THP\Types;


class THPBoolean extends THObject
{
    private $value = null;

    public function __construct($value)
    {
        $this->value = $value;
    }
}