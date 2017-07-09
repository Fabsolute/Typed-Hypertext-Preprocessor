<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 09/07/2017
 * Time: 13:16
 */

namespace THP\Parser;


use THP\Constants\TokenTypes;

class Lexer
{
    /**
     * @param string $code
     * @return Token[]
     * @throws \Exception
     */
    public static function GenerateTokens($code)
    {
        $tokens = [];

        $current_point = 0;
        $end_point = strlen($code);

        while ($current_point < $end_point) {
            $current_character = $code[$current_point];
            if (ctype_alpha($current_character)) {
                $value = '';
                do {
                    $value .= $code[$current_point];
                    $current_point++;
                } while ($current_point < $end_point && ctype_alnum($code[$current_point]));

                if ($value === "true") {
                    $tokens[] = new Token(TokenTypes::TRUE);
                } else if ($value === "false") {
                    $tokens[] = new Token(TokenTypes::FALSE);
                } else {
                    $tokens[] = new Token(TokenTypes::IDENTIFIER, $value);
                }
            } else if (ctype_space($current_character)) {
                $current_point++;
            } else if (ctype_digit($current_character)) {
                $value = '';
                do {
                    $value .= $code[$current_point];
                    $current_point++;
                } while ($current_point < $end_point && ctype_digit($code[$current_point]));
                $tokens[] = new Token(TokenTypes::NUMBER, $value);
            } else {
                switch ($current_character) {
                    case '"':
                        $value = '';
                        $current_point++;
                        do {
                            $value .= $code[$current_point];
                            $current_point++;
                        } while ($current_point < $end_point && $code[$current_point] !== '"');
                        $tokens[] = new Token(TokenTypes::STRING, $value);
                        break;
                    case '+':
                        $tokens[] = new Token(TokenTypes::PLUS);
                        $current_point++;
                        break;
                    case '-':
                        $tokens[] = new Token(TokenTypes::MINUS);
                        $current_point++;
                        break;
                    case '*':
                        $tokens[] = new Token(TokenTypes::TIMES);
                        $current_point++;
                        break;
                    case '/':
                        $tokens[] = new Token(TokenTypes::DIVIDE);
                        $current_point++;
                        break;
                    case '=':
                        $tokens[] = new Token(TokenTypes::EQUALS);
                        $current_point++;
                        break;
                    case ';':
                        $tokens[] = new Token(TokenTypes::SEMICOLON);
                        $current_point++;
                        break;
                    case ',':
                        $tokens[] = new Token(TokenTypes::COMMA);
                        $current_point++;
                        break;
                    case '(':
                        $tokens[] = new Token(TokenTypes::LEFT_PARENTHESIS);
                        $current_point++;
                        break;
                    case ')':
                        $tokens[] = new Token(TokenTypes::RIGHT_PARENTHESIS);
                        $current_point++;
                        break;
                    default:
                        throw new \Exception('Unexpected character: ' . $current_character);
                }
            }
        }

        return $tokens;
    }
}