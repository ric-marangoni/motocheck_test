<?php

namespace App\Application\Helper;

class ParseHelper
{
    /**
     * @param $text
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function content($text, $data)
    {
        preg_match_all('#({{(?>[^{}]|(?0))*?}})#', $text, $matches);

        foreach ($matches[0] as $tag) {
            $variable = preg_replace('/[^a-zA-Z0-9_]/', '', $tag);

            if (!isset($data[$variable])) {
                throw new \Exception("Variable {$variable} not found ");
            }

            $text = str_replace($tag, $data[$variable], $text);
        }

        return $text;
    }
}
