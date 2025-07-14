<?php

namespace MLD\SimpleEquationSolver;

class Solver
{
    /**
     * Solve Postfix (RPN)
     *
     * This function will solve a RPN array
     *
     * @link http://en.wikipedia.org/wiki/Reverse_Polish_notation Postix Notation
     * @param string[] $equation RPN formatted array.
     * @param (int|double|string)[] $variables Variables to be used in the equation.
     * @throws \Exception on division by 0
     * @return double Result of the operation.
     */
    public static function postfix($equation, $variables = [])
    {
        $pf = array_values($equation);

        // create our temporary function variables
        $temp = [];
        $hold = 0;

        // Loop through each number/operator
        $counter = count($pf);

        // Loop through each number/operator
        for ($i = 0; $i < $counter; $i++) {
            // If pf[$i] isn't an operator, add it to the temp array as a holding place
            if (!in_array($pf[$i], [
                Equation::MULTIPLICATION,
                Equation::DIVISION,
                Equation::MODULO,
                Equation::ADDITION,
                Equation::SUBTRACTION
            ], true)) {

                if (is_numeric($pf[$i])) {
                    // if pf[$i] is numeric, just add it to the temp array
                    $temp[$hold++] = (double)$pf[$i];
                } elseif (isset($variables[$pf[$i]])) {
                    // if pf[$i] is a string and only contains [a-zA-Z0-9_] characters, check if it exists in the variables array and replace it with its value
                    $temp[$hold++] = (double)$variables[$pf[$i]];
                } else {
                    // throw an exception if pf[$i] is neither numeric nor a valid variable
                    throw new \Exception(
                        sprintf("Variable '%s' not found in variables array.", $pf[$i]),
                        Equation::ERROR_VARIABLES_MISSING
                    );
                }
            }
            else {
                // perform the operator on the last two numbers
                switch ($pf[$i]) {
                    case '+':
                        $temp[$hold - 2] = (double)($temp[$hold - 2] + $temp[$hold - 1]);

                        break;
                    case '-':
                        $temp[$hold - 2] = (double)($temp[$hold - 2] - $temp[$hold - 1]);
                        break;
                    case '*':
                        $temp[$hold - 2] = (double)($temp[$hold - 2] * $temp[$hold - 1]);
                        break;
                    case '/':
                        if ($temp[$hold - 1] == 0.0) {
                            // todo: throw DivisionByZeroException when dropping PHP 5.6 support
                            throw new \Exception(
                                sprintf(
                                    "Division by 0 on: '%s / %s' in %s",
                                    $temp[$hold - 2],
                                    $temp[$hold - 1],
                                    var_export($equation, true)
                                ),
                                Equation::ERROR_DIVISION_BY_ZERO);
                        }

                        $temp[$hold - 2] = (double)($temp[$hold - 2] / $temp[$hold - 1]);
                        break;
                    case '%':
                        if ($temp[$hold - 1] == 0) {
                            // todo: throw DivisionByZeroException when dropping PHP 5.6 support
                            throw new \Exception(
                                sprintf(
                                    "Division by 0 on: '%s %% %s' in %s",
                                    $temp[$hold - 2],
                                    $temp[$hold - 1],
                                    var_export($equation, true)
                                ),
                                Equation::ERROR_DIVISION_BY_ZERO);
                        }

                        $temp[$hold - 2] = (double)($temp[$hold - 2] % $temp[$hold - 1]);
                        break;
                }

                // Decrease the hold var to one above where the last number is
                $hold -= 1;
            }
        }

        if(!isset($temp[$hold - 1])) {
            // If we don't have a result, throw an exception
            throw new \Exception('No result found in equation', Equation::ERROR_INVALID_EQUATION);
        }

        // return the last number in the array
        return $temp[$hold - 1];
    }
}