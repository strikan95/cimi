<?php

namespace App\ORM\CustomFunctions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;
use PhpParser\Token;

/**
 * ST_DistanceFunction ::= "ST_Distance" "(" Point "," Point "," Unit ")"
 */
class StDistance extends FunctionNode
{
    public $firstPointExpression;
    public $secondPointExpression;

    public $unitExpression;

    public function parse(Parser $parser)
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->firstPointExpression = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->secondPointExpression = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->unitExpression = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        $sql = sprintf(
            'ST_Distance(%s, %s, %s)',
            $this->firstPointExpression->dispatch($sqlWalker),
            $this->secondPointExpression->dispatch($sqlWalker),
            $this->unitExpression->dispatch($sqlWalker)
        );

        return $sql;
    }
}