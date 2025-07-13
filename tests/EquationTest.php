<?php

use MLD\SimpleEquationSolver\Equation;

describe('does basic math', function () {

    it('adds', function () {
        $equation = Equation::parse('1 + 1');
        expect(Equation::solve($equation))->toEqual(2);
    });

    it('subtracts', function () {
        $equation = Equation::parse('9 - 6');
        expect(Equation::solve($equation))->toEqual(3);
    });

    it('multiplies', function () {
        $equation = Equation::parse('5 * 2');
        expect(Equation::solve($equation))->toEqual(10);
    });

    it('divides', function () {
        $equation = Equation::parse('12 / 3');
        expect(Equation::solve($equation))->toEqual(4);
    });

    it('modulus', function () {
        $equation = Equation::parse('10 % 3');
        expect(Equation::solve($equation))->toEqual(1);
    });

    it('adds and subtracts', function () {
        $equation = Equation::parse('3 + 4 - 2');
        expect(Equation::solve($equation))->toEqual(5);
    });

    it('multiplies and divides', function () {
        $equation = Equation::parse('16 * 2 / 4');
        expect(Equation::solve($equation))->toEqual(8);
    });
});

describe('pemdas', function () {
    it('adds and multiplies', function () {
        $equation = Equation::parse('2 + 3 * 4');
        expect(Equation::solve($equation))->toEqual(14);
    });

    it('adds and divides', function () {
        $equation = Equation::parse('2 + 12 / 3');
        expect(Equation::solve($equation))->toEqual(6);
    });

    it('subtracts and multiplies', function () {
        $equation = Equation::parse('10 - 2 * 3');
        expect(Equation::solve($equation))->toEqual(4);
    });

    it('subtracts and divides', function () {
        $equation = Equation::parse('10 - 12 / 3');
        expect(Equation::solve($equation))->toEqual(6);
    });
});

describe('parenthesis', function () {
    it('adds and multiplies', function () {
        $equation = Equation::parse('2 + 3 * (4 - 1)');
        expect(Equation::solve($equation))->toEqual(11);
    });

    it('subtracts and multiplies', function () {
        $equation = Equation::parse('3 * (7 - 3)');
        expect(Equation::solve($equation))->toEqual(12);
    });

    it('adds, subtracts, and multiplies', function () {
        $equation = Equation::parse('2 + 3 * (5 - 4)');
        expect(Equation::solve($equation))->toEqual(5);
    });

    it('adds, multiplies, and parenthesis', function () {
        $equation = Equation::parse('5 + ((1 + 2) * 4) + 3');
        expect(Equation::solve($equation))->toEqual(20);
    });
});

describe('variables', function () {

    it('multiplies', function () {
        $equation = Equation::parse('x * y');
        expect(Equation::solve($equation, ['x' => 5, 'y' => 2]))->toEqual(10);
    });

    it('adds', function () {
        $equation = Equation::parse('x + y');
        expect(Equation::solve($equation, ['x' => 5, 'y' => 2]))->toEqual(7);
    });

    it('subtracts', function () {
        $equation = Equation::parse('x - y');
        expect(Equation::solve($equation, ['x' => 5, 'y' => 2]))->toEqual(3);
    });

    it('divides', function () {
        $equation = Equation::parse('x / y');
        expect(Equation::solve($equation, ['x' => 10, 'y' => 2]))->toEqual(5);
    });
});
