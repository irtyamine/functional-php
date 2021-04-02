<?php

namespace FunctionalPhp\Metadata;

class DefaultMetadataFactory implements MetadataFactoryInterface
{
    public function getMetadataFor(\Closure $closure): ClosureMetadata
    {
        $metadata = new \ReflectionFunction($closure);
        $parameters = array_map(function (\ReflectionParameter $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType() ?: 'mixed';
            $required = !$parameter->isDefaultValueAvailable();
            $default = $required ? null : $parameter->getDefaultValue();

            return new ClosureParameter($name, $type, $required, $default);
        }, $metadata->getParameters());

        return new ClosureMetadata($parameters);
    }
}
