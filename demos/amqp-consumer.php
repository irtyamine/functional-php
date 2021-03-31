<?php

use FunctionalPhp\SessionInterface;

/** @var SessionInterface $session */
$session
    ->read('amqp', ['queue' => 'newsletter.register'])
    ->jsonDecode()
    ->write('mysql', ['table' => 'newslette_registration', 'chunk' => 50])
;
