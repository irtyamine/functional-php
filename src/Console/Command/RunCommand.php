<?php

namespace FunctionalPhp\Console\Command;

use FunctionalPhp\Session;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('run');
        $this->setDescription('Run a session configuration script');
        $this->addArgument('file', InputArgument::REQUIRED, 'Configuration script');
        $this->addOption('monitor');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $file */
        $file = $input->getArgument('file');

        $style = new SymfonyStyle($input, $output);

        if (!file_exists($file)) {
            $style->error(sprintf('The file "%s" does not exist.', $file));

            return 1;
        }

        $logger = new ConsoleLogger($output);
        $session = new Session(logger: $logger);

        require $file;

        $mem = memory_get_peak_usage();
        $time = microtime(true);
        $monitor = null;
        if ($input->getOption('monitor')) {
            $monitor = function ($data) use ($style, &$date) {
                $style->comment(sprintf('%d in push stack, %d generators', $data['push_stack'], $data['generators']));
                $style->table(['ID', 'Running', 'Received', 'Sent'], $data['closures']);
            };
        }
        $session->run($monitor);
        $time = microtime(true) - $time;
        $mem = memory_get_peak_usage() - $mem;

        echo sprintf("Time     : %.6fs\n", $time);
        echo sprintf("Memory   : %.2fMB\n", $mem / 1024 / 1024);

        return 1;
    }
}
