<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 09/07/2017
 * Time: 12:18
 */

namespace THP\Constants;


class TokenTypes
{
    const NULL = 0;
    const IDENTIFIER = 1;
    const NUMBER = 2;
    const STRING = 3;
    const TRUE = 4;
    const FALSE = 5;

    const PLUS = 100;
    const MINUS = 101;
    const TIMES = 102;
    const DIVIDE = 103;
    const EQUALS = 104;

    const SEMICOLON = 200;
    const COMMA = 201;
    const LEFT_PARENTHESIS = 202;
    const RIGHT_PARENTHESIS = 203;
}