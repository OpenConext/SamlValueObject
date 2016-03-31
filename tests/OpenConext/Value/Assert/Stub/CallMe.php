<?php

namespace OpenConext\Value\Assert\Stub;

/**
 * Stub class used in testing valid callables
 */
class CallMe extends CallMeParent
{
    public function instanceCallable()
    {
    }

    public static function staticCallable()
    {
    }

    public static function relativeStaticCall()
    {
    }

    public function __invoke()
    {
    }
}
