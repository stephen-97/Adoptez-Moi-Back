<?php
namespace App\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class ToNumber extends FunctionNode {

    public $field;

    /**
    * Parse DQL Function
    *
    * @param \Doctrine\ORM\Query\Parser $parser
    */

    public function parse (\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
    * Get SQL
    *
    * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
    *
    * @return int
    */
    public function getSql (\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'TO_NUMBER('.$this->field->dispatch($sqlWalker).')';
    }


}