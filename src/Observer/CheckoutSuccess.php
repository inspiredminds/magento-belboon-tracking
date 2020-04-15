<?php

declare(strict_types=1);

/*
 * This file is part of the InspiredMinds_BelboonTracking Magento 2 module.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\BelboonTracking\Observer;

class CheckoutSuccess implements \Magento\Framework\Event\ObserverInterface
{
    private $trackingHelper;
    private $orderRepository;
    private $logger;

    public function __construct(
        \InspiredMinds\BelboonTracking\Helper\BelboonTrackingHelper $trackingHelper,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->trackingHelper = $trackingHelper;
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        try {
            if ($this->trackingHelper->isEnabled() && null !== ($clickId = $this->trackingHelper->getClickId())) {
                $this->trackingHelper->removeClickId();

                $orderId = $observer->getEvent()->getOrderIds()[0];
                /** @var \Magento\Sales\Model\Order $order */
                $order = $this->orderRepository->get($orderId);
                $domain = $this->trackingHelper->getDomain();
                $programId = $this->trackingHelper->getProgramId();
                $timestamp = $order->getUpdatedAt() ? strtotime($order->getUpdatedAt()) : time();

                if (empty($domain) || empty($programId)) {
                    throw new \RuntimeException('Tracking enabled, but no domain or program ID defined.');
                }

                $trackingUrl = sprintf(
                    'https://%s/ts/%s/tsa?typ=s&tst=%s&trc=default&ctg=Request&sid=&cid=%s&orv=%s&cli=%s&orc=%s',
                    $domain,
                    $programId,
                    $timestamp,
                    $order->getRealOrderId(),
                    $order->getGrandTotal(),
                    $clickId,
                    $order->getOrderCurrencyCode()
                );

                $this->logger->debug('Belboon Tracking: '.$trackingUrl);

                (new \GuzzleHttp\Client())->get($trackingUrl);
            }
        } catch (\Exception $e) {
            $this->logger->error('Belboon Tracking: '.$e->getMessage());
        }
    }
}
