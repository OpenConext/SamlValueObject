<?php

namespace OpenConext\Value;

use stdClass;

class TestDataProvider
{
    public static function notString()
    {
        return array(
            'integer' => array(1),
            'float'   => array(1.234),
            'array'   => array(array()),
            'object'  => array(new stdClass())
        );
    }

    public static function notEmptyString()
    {
        return array_merge(
            self::notString(),
            array(
                'empty string'    => array(''),
                'only whitespace' => array('   '),
                'nullbyte'        => array(chr(0)),
            )
        );
    }

    public static function notSingleCharacter()
    {
        return array_merge(
            self::notEmptyString(),
            array(
                'multiple characters' => array('ab')
            )
        );
    }

    public static function invalidRegularExpressionProvider()
    {
        return array(
            'no delimiter'          => array('abc'),
            'no starting delimiter' => array('abc/'),
            'no ending delimiter'   => array('/abc'),
            'no matching delimiter' => array('/abc~'),
            'unknown modifier'      => array('/abc/d'),
        );
    }
}
