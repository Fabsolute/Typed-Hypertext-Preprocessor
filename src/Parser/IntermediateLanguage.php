<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 09/07/2017
 * Time: 15:24
 */

namespace THP\Parser;


use THP\Constants\ILTypes;
use THP\Types\THPBoolean;
use THP\Types\THPInteger;
use THP\Types\THPString;

class IntermediateLanguage
{
    public $commands = [];
    public $objects = [];

    public function load($object)
    {
        $this->objects[] = $object;
        $this->commands[] = ILTypes::LOAD;
        $this->commands[] = $object;
    }

    public function loadInteger($value)
    {
        $this->load(new THPInteger($value));
    }

    public function loadString($value)
    {
        $this->load(new THPString($value));
    }

    public function loadBoolean($value)
    {
        $this->load(new THPBoolean($value));
    }


    public function loadVariable($variable_name)
    {
        $this->commands[] = ILTypes::LOAD_VARIABLE;
        $this->commands[] = $variable_name;
    }

    public function defineVariable($variable_name)
    {
        $this->commands[] = ILTypes::DEFINE_VARIABLE;
        $this->commands[] = $variable_name;
    }

    public function call($parameter_count)
    {
        $this->commands[] = ILTypes::CALL;
        $this->commands[] = $parameter_count;
    }

    public function pushParameter()
    {
        $this->commands[] = ILTypes::PUSH_PARAMETER;
    }

    public function push()
    {
        $this->commands[] = ILTypes::PUSH;
    }

    public function addition()
    {
        $this->commands[] = ILTypes::ADDITION;
    }

    public function subtraction()
    {
        $this->commands[] = ILTypes::SUBTRACTION;
    }

    public function division()
    {
        $this->commands[] = ILTypes::DIVISION;
    }

    public function multiplication()
    {
        $this->commands[] = ILTypes::MULTIPLICATION;
    }

    public function halt()
    {
        $this->commands[] = ILTypes::HALT;
    }
}