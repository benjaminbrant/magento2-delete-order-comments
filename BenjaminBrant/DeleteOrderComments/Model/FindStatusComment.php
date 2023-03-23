<?php

namespace BenjaminBrant\DeleteOrderComments\Model;

use Magento\Framework\App\State;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderInterfaceFactory;
use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Model\Spi\OrderResourceInterface;

class FindStatusComment
{
    /**
     * @var OrderInterfaceFactory
     */
    protected $orderFactory;

    /**
     * @var OrderResourceInterface
     */
    protected $orderResource;

    /**
     * @var OrderRepository
     */
    protected $orderRepo;

    /**
     * @var State
     */
    protected $appState;

    public function __construct(
        OrderInterfaceFactory $orderFactory,
        OrderResourceInterface $orderResource,
        OrderRepository $orderRepo,
        State $appState
    ) {
        $this->orderFactory = $orderFactory;
        $this->orderResource = $orderResource;
        $this->orderRepo = $orderRepo;
        $this->appState = $appState;
    }

    public function showOrderMessages(int $incrementId)
    {
        $order = $this->getOrderByIncrement($incrementId);
        if ($order)
        {
            $orderId = $this->getOrderIdFromOrder($order);

            if ($orderId)
            {
                $this->getAssociatedStatusMessages($orderId);
            }
            else
            {
                return "No valid order provided.";
            }
        }
        else
        {
            return "No valid increment ID provided.";
        }
    }

    /**
     * Output a list of all messages attached to an order with the specified order id
     *
     * @param int $orderId
     * @return string|void
     */
    protected function getAssociatedStatusMessages(int $orderId)
    {
        try
        {
            $order = $this->orderRepo->get($orderId);
            $messages = $order->getStatusHistoryCollection();
            if (count($messages) > 0)
            {
                foreach ($messages as $message)
                {
                    print_r($message->getData());
                }
            }
            else
            {
                echo "No messages exist on order";
            }
        }
        catch (InputException $e) {}
        catch (NoSuchEntityException $e)
        {
            return $e->getMessage();
        }
    }


    /**
     * Retrieves an instance of an order via the order factory populated through resource.
     * Requires manually setting the area code otherwise it errors
     *
     * @param int $increment
     * @return false|OrderInterface
     */
    protected function getOrderByIncrement(int $increment)
    {
        if (!empty($increment))
        {
            try {
                $this->appState->getAreaCode();
            } catch (LocalizedException $e) {
                $this->appState->setAreaCode('adminhtml');
            }
            $order = $this->orderFactory->create();
            $this->orderResource->load($order, $increment, OrderInterface::INCREMENT_ID);
            return $order;
        }
        else
        {
            //No increment id provided
            return false;
        }
    }

    /**
     * Uses an instance of the order interface to return the order ID (Entity ID value)
     *
     * @param OrderInterface $order
     * @return false|int|null
     */
    protected function getOrderIdFromOrder(OrderInterface $order)
    {
        if ($order->getEntityId())
        {
            return $order->getEntityId();
        }
        else
        {
            //No valid order provided
            return false;
        }
    }

}
