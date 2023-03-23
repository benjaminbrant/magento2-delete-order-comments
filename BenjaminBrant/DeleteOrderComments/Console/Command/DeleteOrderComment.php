<?php

namespace BenjaminBrant\DeleteOrderComments\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Console\Cli;
use BenjaminBrant\DeleteOrderComments\Model\DeleteStatusComment;

class DeleteOrderComment extends Command
{
    const INPUT_COMMENT_ID = "commentId";

    protected $deleteStatusComment;

    public function __construct(DeleteStatusComment $deleteStatusComment)
    {
        $this->deleteStatusComment = $deleteStatusComment;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('benjaminbrant:ordercomment:deleteordercomment')
            ->setDescription('Delete a comment added to an order by providing the ID of the comment')
            ->addArgument(
                self::INPUT_COMMENT_ID,
                InputArgument::REQUIRED,
                'Single Comment ID'
            );
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * Get ID of the comment to delete
         */
        $commentId = $input->getArgument(self::INPUT_COMMENT_ID);

        /**
         * Attempt to delete comment
         */
        $result = false;
        if (!empty($commentId))
        {
            $result = $this->deleteStatusComment->removeComment($commentId);
            return ($result) ? Cli::RETURN_SUCCESS : Cli::RETURN_FAILURE;
        }
        else
        {
            //A comment ID was not provided
            return Cli::RETURN_FAILURE;
        }

    }
}
