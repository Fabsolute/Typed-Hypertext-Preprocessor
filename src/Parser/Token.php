<?php

namespace THP\Parser;

class Token
{
    public $token_type = 0;
    public $value = null;

    public function __construct($token_type, $value = null)
    {
        $this->token_type = $token_type;
        $this->value = $value;
    }
}