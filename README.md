# Simple Equation Solver

[![CI](https://github.com/mld/simple-equation-solver/actions/workflows/ci.yml/badge.svg)](https://github.com/mld/simple-equation-solver/actions/workflows/ci.yml)

A PHP library for solving simple equations with variables without using `eval()`.

It both converts equations from text (infix) format to RPN (postfix) format and solves RPN formatted equations, optionally with variables.

Supports addition, subtraction, multiplication, division, modulus, parentheses and scalar variables.

## Inspirations
- [EOS](https://github.com/jlawrence11/eos) by jlawrence11
- [TextCalculate](https://github.com/bolstad/textcalculate) by bolstad