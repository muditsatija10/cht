<?php

namespace Somtel\PipBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PipUpdateOrdersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pip:update-orders')
            ->setDescription('Update all unpaid orders.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pipFacade = $this->getContainer()->get('somtel_pip.cashin_facade');
        $pipFacade->checkForStatusChanges();
    }
}
