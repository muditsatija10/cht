<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('payment:consumer')
            ->setDescription('Start payment consumer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Status: Working...</info>');

        $container = $this->getContainer();

        $consumer = $container->get('app.consumer.payment');

        while (true) {
            // todo: add code for debug mode
            $consumer->consumeQueue();
        }

        return 0;
    }
}
