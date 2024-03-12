<?php

namespace App\Shared\ORM\Spatial\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

/**
 * ST_GeomFromText ::= "ST_GeomFromText" "(" Point "," SRID ")"
 */
class StGeomFromText extends FunctionNode
{
    public $pointExpression;
    public $sridExpression;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->pointExpression = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->sridExpression = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        $sql = sprintf(
            'ST_GeomFromText(%s, %s)',
            $this->pointExpression->dispatch($sqlWalker),
            $this->sridExpression->dispatch($sqlWalker),
        );

        return $sql;
    }
}
