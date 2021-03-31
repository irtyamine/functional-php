<?php

use PhpCsFixer\Config;
use Symfony\Component\Finder\Finder;

$config = new Config();
$config->setFinder(Finder::create()->in([
  __DIR__.'/src',
  __DIR__.'/tests',
]));

return $config;
