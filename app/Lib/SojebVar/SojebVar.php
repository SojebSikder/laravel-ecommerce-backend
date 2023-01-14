<?php

namespace App\Lib\SojebVar;


/**
 * Variable parser
 * @example
 * <code>
 * <?php
 * $text = 'my name is ${name} and I am ${age} years old'
 * SojebVar::addVariable([
 *   'name' => 'sojeb',
 *   'age' => 20,
 * ]);
 * $text = SojebVar::parse($text);
 * ?>
 * <code>
 * @author Sojeb Sikder <sojebsikder@gmail.com>
 */
class SojebVar
{
    private static $_variables = [];
    /**
     * Set custom variable values
     */
    static function addVariable($vars)
    {
        // self::$_variables = $vars;
        self::$_variables = (object) array_merge((array)self::$_variables, $vars);
    }

    static function getVariable()
    {
        return self::$_variables;
    }

    /**
     * Parse text
     * ${name} -> sojeb
     */
    static function parse($text)
    {
        $parsedText = $text;

        preg_match_all('~\${\s*(.+?)\s*\}~is', $parsedText, $matches, PREG_SET_ORDER);

        foreach ($matches as $value) {
            foreach (self::$_variables as $key => $val) {
                switch ($value[1]) {
                    case $key:
                        $parsedText = str_replace($value[0], $val, $parsedText);
                        break;

                    default:
                        break;
                }
            }
        }
        return $parsedText;
    }
}
