<?php

namespace MLD\SimpleEquationSolver;

class Equation
{
    const ERROR_UNMATCHED_BRACES = 1000;

    const ERROR_DIVISION_BY_ZERO = 1001;

    const ERROR_NO_EQUATION = 1002;

    const ERROR_VARIABLES_MISSING = 1003;

    const ERROR_INVALID_EQUATION = 1004;

    const SEPARATOR_OPEN = '(';

    const SEPARATOR_CLOSE = ')';

    const MULTIPLICATION = '*';

    const DIVISION = '/';

    const MODULO = '%';

    const ADDITION = '+';

    const SUBTRACTION = '-';

    /**
     * Parses a mathematical equation and returns the result.
     *
     * @param string $equation The equation to parse in infix notation.
     * @return string[] The result of the parsed equation in postfix (RPN) notation.
     * @throws \Exception on malformed equation, division by zero, or missing variables.
     */
    static public function parse($equation)
    {
        // Convert the infix equation to postfix notation
        return Parser::infix2postfix($equation);
    }

    /**
     * Solves a mathematical equation, optionally using provided variables.
     * @param string|string[] $equation The equation to solve, either as a string in infix notation or an array in postfix notation.
     * @param (int|double)[] $variables An associative array of variables to use in the equation, where keys are variable names and values are their corresponding values.
     * @return int|double The result of the solved equation.
     * @throws \Exception
     */
    static public function solve($equation, $variables = [])
    {
        if (!is_array($equation)) {
            // If the equation is not an array, assume it's a string and parse it
            $equation = self::parse($equation);
        }

        return Solver::postfix($equation, $variables);
    }
}