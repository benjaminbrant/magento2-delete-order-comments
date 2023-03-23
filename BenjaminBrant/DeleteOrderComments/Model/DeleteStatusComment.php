<?php

namespace BenjaminBrant\DeleteOrderComments\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Sales\Api\Data\OrderStatusHistoryInterface;
use Magento\Sales\Api\OrderStatusHistoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Logger\Monolog;

class DeleteStatusComment
{
    /**
     * @var OrderStatusHistoryRepositoryInterface
     */
    protected $orderStatusRepository;

    /**
     * @var Monolog
     */
    protected $logger;

    public function __construct(
        OrderStatusHistoryRepositoryInterface $orderStatusRepository,
        Monolog $logger
    ) {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->logger = $logger;
    }

    /**
     * @param int $commentId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function removeComment(int $commentId)
    {
        $comment = $this->getStatusCommentObject($commentId);
        return $this->deleteCommentFromOrder($comment);
    }

    /**
     * @param int $commentId
     * @return OrderStatusHistoryInterface|null
     */
    protected function getStatusCommentObject(int $commentId)
    {
        $orderStatusCommentObject = null;

        if(!empty($commentId))
        {
            try
            {
                $orderStatusCommentObject = $this->orderStatusRepository->get($commentId);
                $this->logger->info("Order comment object successfully created using $commentId");
            }
            catch (NoSuchEntityException $exception)
            {
                $this->logger->error($exception->getMessage());
            }
        }
        else
        {
            $this->logger->error("No valid commend ID supplied.");
        }

        return $orderStatusCommentObject;
    }

    /**
     * @param OrderStatusHistoryInterface $orderStatusCommentObject
     * @return bool
     */
    protected function deleteCommentFromOrder(OrderStatusHistoryInterface $orderStatusCommentObject)
    {
        $result = false;
        try
        {
            $result = $this->orderStatusRepository->delete($orderStatusCommentObject);
            $this->logger->info("The order comment was successfully deleted");
        }
        catch (CouldNotDeleteException $exception)
        {
            $this->logger->error("Problem encountered while deleting the comment", [$exception->getMessage()]);
        }
        return $result;
    }
}
