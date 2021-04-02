<?php

namespace FunctionalPhp\Metadata;

interface MetadataFactoryInterface
{
    public function getMetadataFor(\Closure $closure): ClosureMetadata;
}
