<?php

namespace FunctionalPhp\Console;

use FunctionalPhp\Console\Command\RunCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

class Application extends ConsoleApplication
{
    public function __construct()
    {
        parent::__construct('Functional PHP', 'DEV');
        $this->addCommands([
            new RunCommand(),
        ]);
    }
}
