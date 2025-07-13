<?php

use MLD\SimpleEquationSolver\Equation;
use MLD\SimpleEquationSolver\Solver;

describe('basic math', function () {
    it('adds', function () {
        expect(Solver::postfix([1, 1, '+']))->toEqual(2);
    });

    it('subtracts', function () {
        expect(Solver::postfix([9, 6, '-']))->toEqual(3);
    });

    it('multiplies', function () {
        expect(Solver::postfix([5, 2, '*']))->toEqual(10);
    });

    it('divides', function () {
        expect(Solver::postfix([12, 3, '/']))->toEqual(4);
    });

    it('adds and subtracts', function () {
        expect(Solver::postfix([3, 4, '+', 2, '-']))->toEqual(5);
    });

    it('multiplies and divides', function () {
        expect(Solver::postfix([16, 2, '*', 4, '/']))->toEqual(8);
    });
});

describe('variables', function () {

    it('multiplies', function () {
        expect(Solver::postfix(['x', 'y', '*'], ['x' => 5, 'y' => 2]))->toEqual(10);
    });

    it('adds', function () {
        expect(Solver::postfix(['x', 'y', '+'], ['x' => 5, 'y' => 2]))->toEqual(7);
    });

    it('subtracts', function () {
        expect(Solver::postfix(['x', 'y', '-'], ['x' => 5, 'y' => 2]))->toEqual(3);
    });

    it('divides', function () {
        expect(Solver::postfix(['x', 'y', '/'], ['x' => 10, 'y' => 2]))->toEqual(5);
    });
});

describe('exceptions', function () {
    it('throws a division by zero exception', function () {
        Solver::postfix([3, 0, '/']);
    })->throws(
        Exception::class, null, Equation::ERROR_DIVISION_BY_ZERO
    );

    it('throws a modulus by zero exception', function () {
        Solver::postfix([3, 0, '%']);
    })->throws(
        Exception::class, null, Equation::ERROR_DIVISION_BY_ZERO
    );

    it('missing variable exception', function () {
        Solver::postfix(['4', 'x', '*']);
    })->throws(
        Exception::class, null, Equation::ERROR_VARIABLES_MISSING
    );

    it('missing equation exception', function () {
        echo "missing equation exception: " . Solver::postfix([]);
    })->throws(
        Exception::class, null, Equation::ERROR_INVALID_EQUATION
    );

});