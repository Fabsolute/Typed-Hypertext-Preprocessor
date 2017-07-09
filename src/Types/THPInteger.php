<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 09/07/2017
 * Time: 15:22
 */

namespace THP\Types;


class THPInteger extends THObject
{
    private $value = null;

    public function __construct($value)
    {
        $this->value = $value;
    }
}