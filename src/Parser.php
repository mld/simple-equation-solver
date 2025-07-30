<?php
declare(strict_types=1);

namespace MLD\SimpleEquationSolver;

class Parser
{
    const VALID_PATTERN = '/[^0-9+\-*\/%().a-zA-Z_\s]/';

    const VALID_PATTERN_ALLOW_WHITESPACE = '/[^0-9+\-*\/%().a-zA-Z_]/';

    const TOKEN_PATTERN = '/\d+|\d+.\d+|[a-zA-Z]{1}\w*|[+\-*\/%]|[()]/';

    const NUMERIC_PATTERN = '/^\d+(\.\d+)?$/';

    const VARIABLE_PATTERN = '/^[a-zA-Z]\w*$/';

    /**
     * Infix to Postfix
     *
     * Converts an infix (standard) equation to postfix (RPN) notation.
     *
     * @link http://en.wikipedia.org/wiki/Infix_notation Infix Notation
     * @link http://en.wikipedia.org/wiki/Reverse_Polish_notation Reverse Polish Notation
     * @param string $infix A standard notation equation
     * @throws \Exception on parsing and matching errors
     * @return string[] Fully formed RPN Stack
     */
    public static function infix2postfix($infix)
    {
        //check to make sure 'valid' equation
        self::checkInfix($infix);

        // remove all unsupported characters
        $infix = self::cleanInfix($infix);

        // insert implicit multiplication where needed
        $infix = self::insertImplicitMultiplication($infix);

        // Use preg_match_all to find all tokens in the infix expression
        if (!preg_match_all(self::TOKEN_PATTERN, $infix, $matches)) {
            // If the regex does not match, throw an exception
            throw new \Exception('Malformed equation: ' . $infix, Equation::ERROR_INVALID_EQUATION);
        }

        $postfix = [];
        $stack = [];

        // convert infix from matches[0] to a postfix array
        foreach ($matches[0] as $token) {
            if (trim($token) === '') {
                continue; // Skip empty tokens
            }

            if (
                preg_match(self::NUMERIC_PATTERN, $token) // numeric
                ||
                preg_match(self::VARIABLE_PATTERN, $token) // variable
            ) {
                // Operand: (not a number or variable) Add directly to the output
                $postfix[] = $token;
            } elseif ($token === Equation::SEPARATOR_OPEN) {
                // Opening parenthesis: Push onto the stack
                $stack[] = $token;
            } elseif ($token === Equation::SEPARATOR_CLOSE) {
                // Closing parenthesis: Pop from the stack to the output until '(' is found
                while ($stack !== [] && end($stack) !== Equation::SEPARATOR_OPEN) {
                    $postfix[] = array_pop($stack);
                }

                array_pop($stack); // Remove the '('
            } else {
                // Operator: Pop operators with higher or equal precedence, then push the current operator
                while ($stack !== [] && self::getPrecedence(end($stack)) >= self::getPrecedence($token)) {
                    $postfix[] = array_pop($stack);
                }

                $stack[] = $token;
            }
        }

        // After scanning, pop remaining operators from the stack to the output
        while ($stack !== []) {
            $postfix[] = array_pop($stack);
        }

        // remove any null values from the postfix array
        $postfix = array_filter($postfix, fn($value) => $value !== null);

        return $postfix;
    }

    /**
     * Insert Implicit Multiplication
     *
     * Inserts implicit multiplication operators where necessary in the infix expression.
     *
     * This function adds a multiplication operator between numbers and variables, or between
     * variables and parentheses, to ensure that the expression is correctly interpreted.
     *
     * @param string $infix The infix expression to process
     * @return string The modified infix expression with implicit multiplications added
     * @throws \Exception if the regex replacement fails
     */
    private static function insertImplicitMultiplication($infix)
    {
        $infix = preg_replace(
            [
                '/(\d)([a-zA-Z(])/',
                '/([a-zA-Z])(\d|\()/',
                '/(\))(\d|[a-zA-Z(])/',
            ],
            '$1*$2',
            $infix
        );

        if ($infix === null) {
            throw new \Exception('Malformed equation: ' . $infix, Equation::ERROR_INVALID_EQUATION);
        }

        return $infix;
    }

    /**
     * Get Precedence
     *
     * Returns the precedence of an operator.
     *
     * @param string $operator The operator to check
     * @return int The precedence of the operator, or 0 if not found
     */
    private static function getPrecedence($operator)
    {
        $precedence = [
            Equation::ADDITION => 1,
            Equation::SUBTRACTION => 1,
            Equation::MULTIPLICATION => 2,
            Equation::DIVISION => 2,
            Equation::MODULO => 2,
        ];
        return $precedence[$operator] ?? 0;
    }

    /**
     * Check Infix for basic errors.
     *
     * Two simple checks are performed:
     * 1. If the equation is empty, an exception is thrown.
     * 2. If the number of opening parentheses does not match the number of closing parentheses, an exception is thrown.
     *
     * @param string $infix Equation to check
     * @return void true if passes - throws an exception if not.
     * @throws \Exception if malformed.
     */
    private static function checkInfix($infix)
    {
        if (trim($infix) === '') {
            throw new \Exception('No Equation given', Equation::ERROR_NO_EQUATION);
        }

        //Make sure we have the same number of '(' as we do ')'
        if (substr_count($infix, Equation::SEPARATOR_OPEN) !== substr_count($infix, Equation::SEPARATOR_CLOSE)) {
            throw new \Exception('Mismatched parenthesis in ' . $infix, Equation::ERROR_UNMATCHED_BRACES);
        }
    }

    /**
     * Clean up the infix equation
     *
     * Removes all characters that are not numbers, operators, parentheses, or variables.
     *
     * @param string $infix A standard notation equation
     * @param bool $keepWhitespace Whether to keep whitespace in the equation
     * @return string The cleaned-up equation
     * @throws \Exception if the cleaned-up equation is malformed
     */
    public static function cleanInfix($infix, $keepWhitespace = false)
    {
        if ($keepWhitespace) {
            // If we want to keep whitespace, we will only remove unsupported characters
            $infix = preg_replace(self::VALID_PATTERN, '', $infix);
        } else {
            // Otherwise, remove all unsupported characters, including whitespace
            $infix = preg_replace(self::VALID_PATTERN_ALLOW_WHITESPACE, '', $infix);
        }

        if ($infix === null) {
            throw new \Exception('Malformed equation: ' . $infix, Equation::ERROR_INVALID_EQUATION);
        }

        return $infix;
    }
}