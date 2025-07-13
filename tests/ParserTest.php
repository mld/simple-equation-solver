<?php

use MLD\SimpleEquationSolver\Parser;

describe('cleans infix', function () {

    it('cleans up an equation', function () {
        $equation = '2 + 3 * 4 - 5 / 2';
        $cleaned = Parser::cleanInfix($equation);
        expect($cleaned)->toEqual('2+3*4-5/2');

        $equation = '2 + $3 * 4.0 - 5 / 2';
        $cleaned = Parser::cleanInfix($equation);
        expect($cleaned)->toEqual('2+3*4.0-5/2');

        $equation = '2 + ap * 4 - 5 / 2';
        $cleaned = Parser::cleanInfix($equation);
        expect($cleaned)->toEqual('2+ap*4-5/2');
    });
});

it('converts infix to postfix', function () {
    expect(Parser::infix2postfix('1+1'))->sequence(1, 1, '+');
    expect(Parser::infix2postfix('1+1(48x)'))->sequence(1, 1, 48, 'x', '*', '*', '+');
    expect(Parser::infix2postfix('x+y'))->sequence('x', 'y', '+');
});
