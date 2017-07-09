<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 09/07/2017
 * Time: 15:06
 */

namespace THP\Parser;


use Fabs\LINQ\Exception\Exception;
use Fabs\LINQ\LINQ;
use THP\Constants\ReturnTypes;
use THP\Constants\TokenTypes;

class Parser
{
    private $operators = [];
    private $tokens = [];
    private $current_token_point = 0;

    /** @var IntermediateLanguage */
    private $output = null;

    public function __construct()
    {
        $this->output = new IntermediateLanguage();
        $this->operators[] = new Operator(TokenTypes::PLUS, 2);
        $this->operators[] = new Operator(TokenTypes::MINUS, 2);
        $this->operators[] = new Operator(TokenTypes::TIMES, 3);
        $this->operators[] = new Operator(TokenTypes::DIVIDE, 3);
    }

    /**
     * @param string $code
     * @return IntermediateLanguage
     * @throws Exception
     */
    public function parse($code)
    {
        $this->tokens = Lexer::GenerateTokens($code);

        while ($this->valid()) {
            switch ($this->getCurrentToken()->token_type) {
                case TokenTypes::IDENTIFIER:
                    $name = $this->getCurrentToken()->value;
                    $this->current_token_point++;
                    $parameter_count = 0;
                    if ($this->getCurrentToken()->token_type === TokenTypes::EQUALS) {
                        $this->current_token_point++;
                        if ($this->calculateProcess() === ReturnTypes::EXPRESSION) {
                            if ($this->getCurrentToken()->token_type === TokenTypes::SEMICOLON) {
                                $this->output->defineVariable($name);
                                $this->current_token_point++;
                            } else {
                                throw new Exception($name . ' den sonra ; bekleniyordu');
                            }
                        } else {
                            throw new Exception($name . ' =  den sonra deger girilmelidir.');
                        }
                    } else if ($this->getFunction($parameter_count)) {
                        $this->output->loadString($name);
                        $this->output->call($parameter_count);
                    } else {
                        throw new Exception($name . ' degiskeninin ne isi var burada abi :D');
                    }
                    break;
                default:
                    throw new Exception('beklenmedik bir tip geldi ' . $this->getCurrentToken()->token_type);
                    break;
            }
        }

        return $this->output;
    }

    private function calculateProcess()
    {
        /** @var Operator[] $operators */
        $operators = [];
        $has_expression = false;
        while ($this->valid()) {
            if ($this->getExpression() === ReturnTypes::EXPRESSION) {
                $has_expression = true;
            } else if (!$has_expression) {
                break;
            } else {
                $current_token = $this->getCurrentToken();
                /** @var Operator $operator */
                $operator = LINQ::from($this->operators)->firstOrDefault(function ($operator) use ($current_token) {
                    /** @var Operator $operator */
                    return $operator->operator_type === $current_token->token_type;
                });

                if ($operator != null) {
                    $priority = $operator->priority;
                    while (count($operators) > 0 && $priority < $operators[count($operators) - 1]->priority) {
                        $op = $operators[count($operators)];
                        switch ($op->operator_type) {
                            case TokenTypes::PLUS:
                                $this->output->addition();
                                break;
                            case TokenTypes::MINUS:
                                $this->output->subtraction();
                                break;
                            case TokenTypes::DIVIDE:
                                $this->output->division();
                                break;
                            case TokenTypes::TIMES:
                                $this->output->multiplication();
                                break;
                        }
                        $operators = array_slice($operators, 0, count($operators) - 1);
                    }
                    $this->output->push();
                    $operators[] = new Operator($this->getCurrentToken()->token_type, $priority);
                    $this->current_token_point++;
                    $has_expression = false;
                } else {
                    break;
                }
            }
        }

        if (!$has_expression) {
            throw new Exception('expression bekleniyordu');
        }

        $operators = array_reverse($operators);

        foreach ($operators as $operator) {
            switch ($operator->operator_type) {
                case TokenTypes::PLUS:
                    $this->output->addition();
                    break;
                case TokenTypes::MINUS:
                    $this->output->subtraction();
                    break;
                case TokenTypes::DIVIDE:
                    $this->output->division();
                    break;
                case TokenTypes::TIMES:
                    $this->output->multiplication();
                    break;
            }
        }

        return ReturnTypes::EXPRESSION;
    }

    private function getExpression()
    {
        switch ($this->getCurrentToken()->token_type) {
            case TokenTypes::NUMBER:
                $this->output->loadInteger($this->getCurrentToken()->value);
                $this->current_token_point++;
                return ReturnTypes::EXPRESSION;
            case TokenTypes::STRING:
                $this->output->loadString($this->getCurrentToken()->value);
                $this->current_token_point++;
                return ReturnTypes::EXPRESSION;
            case TokenTypes::TRUE:
                $this->output->loadBoolean(true);
                $this->current_token_point++;
                return ReturnTypes::EXPRESSION;
            case TokenTypes::FALSE:
                $this->output->loadBoolean(false);
                $this->current_token_point++;
                return ReturnTypes::EXPRESSION;
            case TokenTypes::IDENTIFIER:
                $variable_name = $this->getCurrentToken()->value;
                $this->current_token_point++;
                $parameter_count = 0;
                if ($this->getFunction($parameter_count)) {
                    $this->output->loadVariable($variable_name);
                    $this->output->call($parameter_count);
                } else {
                    $this->output->loadVariable($variable_name);
                }
                return ReturnTypes::EXPRESSION;
        }
        return ReturnTypes::NONE;
    }

    private function getFunction(&$parameter_count)
    {
        if ($this->getCurrentToken()->token_type === TokenTypes::LEFT_PARENTHESIS) {
            $this->current_token_point++;
        } else {
            return false;
        }

        if ($this->getCurrentToken()->token_type !== TokenTypes::RIGHT_PARENTHESIS) {
            while ($this->valid()) {
                if ($this->calculateProcess() === ReturnTypes::NONE) {
                    throw new \Exception('expression bekleniyordu : ' . $this->getCurrentToken()->token_type);
                }

                $parameter_count++;
                $this->output->pushParameter();

                if ($this->getCurrentToken()->token_type === TokenTypes::COMMA) {
                    $this->current_token_point++;
                } else {
                    break;
                }
            }

            if (!$this->valid() || $this->getCurrentToken()->token_type !== TokenTypes::RIGHT_PARENTHESIS) {
                throw new \Exception('parantez kapatilmadi');
            }
        }

        $this->current_token_point++;
        if ($this->getCurrentToken()->token_type !== TokenTypes::SEMICOLON) {
            throw new \Exception('; hatasi');
        } else {
            $this->current_token_point++;
            $this->output->call($parameter_count);
            return true;
        }
    }

    /**
     * @return Token
     */
    private function getCurrentToken()
    {
        return $this->tokens[$this->current_token_point];
    }

    private function valid()
    {
        return $this->current_token_point < count($this->tokens);
    }
}