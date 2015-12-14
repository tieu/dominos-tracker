<?php namespace App;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyCommand extends Command {


    public function configure()
    {
        $this->setName('notify')
            ->setDescription('Get notification for order on status change.')
            ->addArgument('orderid', InputArgument::REQUIRED);
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $orderid = $input->getArgument('orderid');
        $output->writeln($this->notifyOnOrderWith($orderid));
    }

}