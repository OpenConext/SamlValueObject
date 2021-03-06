<?php

namespace OpenConext\Value;

use stdClass;

class TestDataProvider
{
    public static function notInteger()
    {
        return array_merge(
            self::emtpyString(),
            array(
                'float'   => array(1.234),
                'true'    => array(true),
                'false'   => array(false),
                'array'   => array(array()),
                'object'  => array(new stdClass()),
                'null'    => array(null),
                'string'  => array('string')
            )
        );
    }

    public static function notString()
    {
        return array(
            'integer' => array(1),
            'float'   => array(1.234),
            'true'    => array(true),
            'false'   => array(false),
            'array'   => array(array()),
            'object'  => array(new stdClass()),
            'null'    => array(null)
        );
    }

    public static function notBoolean()
    {
        return array_merge(
            self::emtpyString(),
            array(
                'integer' => array(1),
                'float'   => array(1.234),
                'array'   => array(array()),
                'object'  => array(new stdClass()),
                'null'    => array(null),
                'string'  => array('string')
            )
        );
    }

    public static function notArray()
    {
        return array_merge(
            self::emtpyString(),
            array(
                'integer' => array(1),
                'float'   => array(1.234),
                'true'    => array(true),
                'false'   => array(false),
                'object'  => array(new stdClass()),
                'null'    => array(null),
                'string'  => array('string')
            )
        );
    }

    public static function notCallable()
    {
        return array_merge(
            self::emtpyString(),
            array(
                'integer' => array(1),
                'float'   => array(1.234),
                'array'   => array(array()),
                'true'    => array(true),
                'false'   => array(false),
                'object'  => array(new stdClass()),
                'null'    => array(null),
                'string'  => array('string')
            )
        );
    }

    public static function notStringOrEmptyString()
    {
        return array_merge(
            self::notString(),
            self::emtpyString()
        );
    }

    public static function notNullAndNotStringOrEmptyString()
    {
        return array_filter(self::notStringOrEmptyString(), function ($value){
            return reset($value) !== null;
        });
    }

    public static function emtpyString()
    {
        return array(
            'empty string'    => array(''),
            'new line only'   => array("\n"),
            'only whitespace' => array('   '),
            'nullbyte'        => array(chr(0)),
        );
    }

    public static function notSingleCharacter()
    {
        return array_merge(
            self::notStringOrEmptyString(),
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

    public static function notRfc822CompliantEmail()
    {
        return array_merge(
            self::notStringOrEmptyString(),
            array(
                'no @-sign'       => array('mailboxexample.invalid'),
                'no tld'          => array('mailbox@example'),
                'no mailbox'      => array('@example.invalid'),
                'invalid mailbox' => array('(｡◕‿◕｡)@example.invalid'),
            )
        );
    }
}
