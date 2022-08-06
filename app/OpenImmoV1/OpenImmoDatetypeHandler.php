<?php

namespace App\OpenImmoV1;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Context;
use JMS\Serializer\XmlDeserializationVisitor;
use JMS\Serializer\XmlSerializationVisitor;

class OpenImmoDatetypeHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'DateTime',
                'method' => 'serializeDateTimeToXml',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'xml',
                'type' => 'DateTime',
                'method' => 'deserializeDateTimeToXml',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'serializeDateTimeToJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'deserializeDateTimeToJson',
            ],
        ];
    }


    public function serializeDateTimeToXml(XmlSerializationVisitor $visitor, \DateTime $date, array $type, Context $context)
    {
        return $date->format($type['params'][0]);
    }


    public function deserializeDateTimeToXml(XmlDeserializationVisitor $visitor, $dateAsString, array $type, Context $context)
    {
        return new \DateTime($dateAsString);
    }


    public function serializeDateTimeToJson(JsonSerializationVisitor $visitor, \DateTime $date, array $type, Context $context)
    {
        return $date->format($type['params'][0]);
    }


    public function deserializeDateTimeToJson(JsonDeserializationVisitor $visitor, $dateAsString, array $type, Context $context)
    {
        return new \DateTime($dateAsString);
    }
}
