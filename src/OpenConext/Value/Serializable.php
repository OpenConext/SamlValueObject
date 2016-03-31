<?php

namespace OpenConext\Value;

/**
 * Simple interface that allows for (de-)serialization of objects by external serializers
 */
interface Serializable
{
    /**
     * @param mixed $data the data required to deserialize the data into a new object
     * @return static The object instance
     */
    public static function deserialize($data);

    /**
     * @return mixed a representation of the data that the value object contains. This must be compatible with {@see
     *               deserialize}: feeding the data given by serialize into deserialize must recreate an equal object
     */
    public function serialize();
}
