<?php namespace App;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand {

    private $orders;

    public function __construct(OrderAdapter $orders)
    {
        $this->orders = $orders;
        parent::__construct();
    }

    /**
     * Get notification for when order is on the way or complete
     *
     * @param $orderid - id of order you want to check up on
     * @return string
     */
    protected function notifyOnOrderWith($orderid)
    {
        $status = $this->orders->getStatusOf($orderid);
        while ($status != "On Route" && $status != "Complete")
        {
            $status = $this->orders->getStatusOf($orderid);
        }

        return "<info>Order is " . $status . "</info>";
    }

    /**
     * Show all order's id and current status
     *
     * @param $output - output interface
     */
    protected function showOrders($output)
    {
        $orders = $this->orders->fetchAll();

        if (count($orders[0]) == 0)
        {
            $output->writeln("<error>No Orders Found!</error>");

            return false;
        }

        $table = new Table($output);
        $table->setHeaders(['Order ID', 'Status'])
            ->setRows($orders)
            ->render();
    }
}