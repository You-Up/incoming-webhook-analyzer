<?php

// src/AppBundle/Command/CreateUserCommand.php
namespace Webhook\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScaffoldCommand extends Command
{
    protected function configure()
    {
// ...
        $this
            // the name of the command (the part after "bin/console")
            ->setName( 'scaffold' )
            // the short description shown while running "php bin/console list"
            ->setDescription( 'Create the files necessary to handle a new Webhook.' )
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp( 'This command creates the files necessary to add support of a new Webhook.' );
}

    protected function execute( InputInterface $input, OutputInterface $output )
    {
// ...
        // 1. Name of company -> autocomplete
        // 2. Create from the templates
        //      -> the handler
        //      -> the json request
        //      -> the json result

    }
}