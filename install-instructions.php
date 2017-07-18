<?php

echo "\e[1;33m"
 . "************Please run***************\n"
 . " php artisan atlantis:set:db\n"
 . " php artisan atlantis:install\n"
 . "\e[0m";

/*
require_once 'vendor/autoload.php';

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;


$title = '************Please run***************';
$body = 'php artisan atlantis:set:db' . PHP_EOL
        . 'php artisan atlantis:install';

$output = new ConsoleOutput();

$styleTitle = new OutputFormatterStyle('white', 'blue', array('bold', 'underscore'));
$styleBody = new OutputFormatterStyle('white', 'blue', array('bold'));
$output->getFormatter()->setStyle('title', $styleTitle);
$output->getFormatter()->setStyle('body', $styleBody);

$output->writeln('<title>' . $title . '</>');
$output->writeln('<body>' . $body . '</>');
 * 
 */