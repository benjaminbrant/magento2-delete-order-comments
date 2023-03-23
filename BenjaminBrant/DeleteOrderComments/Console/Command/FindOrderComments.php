<?php

namespace BenjaminBrant\DeleteOrderComments\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Console\Cli;
use BenjaminBrant\DeleteOrderComments\Model\FindStatusComment;

class FindOrderComments extends Command
{
    const INPUT_INCREMENT_ID = "incrementId";

    protected $findStatusComment;

    public function __construct(FindStatusComment $findStatusComment)
    {
        $this->findStatusComment = $findStatusComment;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('benjaminbrant:ordercomment:findordercomments')
            ->setDescription('View the comments of an order found via the Increment ID of the order.')
            ->addArgument(
                self::INPUT_INCREMENT_ID,
                InputArgument::REQUIRED,
                'Increment ID of the required order'
            );
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * Get Increment ID of the order to load
         */
        $incrementId = $input->getArgument(self::INPUT_INCREMENT_ID);

        /**
         * Attempt to show associated messages of the order to be loaded
         */
        $result = false;
        if (!empty($incrementId))
        {
            $result = $this->findStatusComment->showOrderMessages($incrementId);
            return ($result) ? Cli::RETURN_SUCCESS : Cli::RETURN_FAILURE;
        }
        else
        {
            //An order was not loaded and associated messages not displayed
            return Cli::RETURN_FAILURE;
        }

    }
}
