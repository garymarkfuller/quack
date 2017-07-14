<?php
/**
 * Quack Compiler and toolkit
 * Copyright (C) 2016 Marcelo Camargo <marcelocamargo@linuxmail.org> and
 * CONTRIBUTORS.
 *
 * This file is part of Quack.
 *
 * Quack is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Quack is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Quack.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace QuackCompiler\Ast\Stmt;

use \QuackCompiler\Parser\Parser;

class ClassStmt extends Stmt
{
    public $name;
    public $body;
    public $native = false;

    public function __construct($name, $body)
    {
        $this->name = $name;
        $this->body = $body;
    }

    public function format(Parser $parser)
    {
        $source = $this->native ? 'native ' : '';
        $source .= 'class ';
        $source .= $this->name;
        $source .= $parser->indent();
        $source .= PHP_EOL;
        $parser->openScope();

        foreach ($this->body as $sign) {
            $source .= $parser->indent()
                . ($sign->is_recursive ? 'rec ' : '')
                . ($sign->is_reference ? '*' : '')
                . $sign->name;

            $source .= '(';
            $source .= implode(', ', array_map(function ($param) {
                return ($param->is_reference ? '*' : '') . $param->name;
            }, $sign->parameters));

            $source .= ')';
            $source .= PHP_EOL;
        }

        $parser->closeScope();
        $source .= $parser->indent();
        $source .= 'end';
        $source .= PHP_EOL;

        return $source;
    }

    public function injectScope(&$parent_scope)
    {
        // Pass :)
    }

    public function runTypeChecker()
    {
        // Pass :)
    }
}
