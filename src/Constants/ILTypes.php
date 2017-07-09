<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 09/07/2017
 * Time: 15:25
 */

namespace THP\Constants;


class ILTypes
{
    const HALT = "HALT";

    const LOAD = "LOAD";
    const LOAD_VARIABLE = "LOAD_VARIABLE";
    const DEFINE_VARIABLE = "DEFINE_VARIABLE";

    const PUSH = "PUSH";
    const PUSH_PARAMETER = "PUSH_PARAMETER";
    const CALL = "CALL";

    const ADDITION = "ADDITION";
    const SUBTRACTION = "SUBTRACTION";
    const DIVISION = "DIVISION";
    const MULTIPLICATION = "MULTIPLICATION";
}