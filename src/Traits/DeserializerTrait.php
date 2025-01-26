<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Traits;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait DeserializerTrait
{
    private ?Serializer $jsonDeserializer = null;

    /**
     * Create a serializer instance for deserializing data.
     *
     * @return Serializer a serializer instance, without any configuration
     */
    public function getJsonDeserializer(): Serializer
    {
        // Instantiate a serializer instance if no instance has been instantiated already
        if ($this->jsonDeserializer === null) {
            // Configure the attribute loader, so the serialized path can be different than the variable name
            $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
            $objectNormalizer = new ObjectNormalizer($classMetadataFactory, null, null, new ReflectionExtractor());

            $this->jsonDeserializer = new Serializer([$objectNormalizer], [new JsonEncoder()]);
        }

        return $this->jsonDeserializer;
    }

    /**
     * Function to deserialize a string into a class string.
     *
     * @template T of object The object that the content is deserialized to.
     *
     * @param string $content the content to deserialize
     * @param class-string<T> $payloadClass the class that describes the format of the deserialized content
     * @param bool $requireAllProps Whether the deserialized $content must contain all properties defined in $payloadClass
     *
     * @return T the deserialized version of the input content
     */
    public function deserializeJson(string $content, string $payloadClass, bool $requireAllProps = true)
    {
        $context = [];
        if ($requireAllProps) {
            $context[AbstractNormalizer::REQUIRE_ALL_PROPERTIES] = true;
        }

        return $this->getJsonDeserializer()->deserialize($content, $payloadClass, 'json', $context);
    }
}
