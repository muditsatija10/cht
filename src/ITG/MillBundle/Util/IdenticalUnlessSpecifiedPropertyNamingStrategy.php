<?php

namespace ITG\MillBundle\Util;

use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;

class IdenticalUnlessSpecifiedPropertyNamingStrategy implements PropertyNamingStrategyInterface
{
    public function translateName(PropertyMetadata $property)
    {
        $name = $property->serializedName;

        if (null !== $name) {
            return $name;
        }

        return $property->name;
    }
}