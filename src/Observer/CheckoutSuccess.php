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
    private $logger;

    public function __construct(
        \InspiredMinds\BelboonTracking\Helper\BelboonTrackingHelper $trackingHelper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->trackingHelper = $trackingHelper;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        try {
            if ($this->trackingHelper->isEnabled() && null !== ($clickId = $this->trackingHelper->getClickId())) {
                $this->trackingHelper->removeClickId();

                /** @var \Magento\Sales\Model\Order $order */
                $order = $observer->getEvent()->getOrder();
                $domain = $this->trackingHelper->getDomain();
                $programId = $this->trackingHelper->getProgramId();
                $timestamp = $order->getUpdatedAt() ? strtotime($order->getUpdatedAt()) : time();

                if (empty($domain) || empty($programId)) {
                    throw new \RuntimeException('Tracking enabled, but no domain or program ID defined.');
                }

                $queryParameter = http_build_query([
                    'typ' => 's',
                    'tst' => $timestamp,
                    'trc' => 'default',
                    'ctg' => 'sale',
                    'sid' => 'checkout',
                    'cid' => $order->getRealOrderId(),
                    'orv' => $order->getGrandTotal(),
                    'cli' => $clickId,
                    'orc' => $order->getOrderCurrencyCode(),
                ]);

                $trackingUrl = 'https://'.$domain.'/ts/'.$programId.'/tsa?'.$queryParameter;

                $this->logger->info('Belboon Tracking: '.$trackingUrl);

                $response = (new \GuzzleHttp\Client())->get($trackingUrl);

                $this->logger->debug('Belboon Tracking: '.$response->getBody());
            }
        } catch (\Exception $e) {
            $this->logger->error('Belboon Tracking: '.$e->getMessage());
        }
    }
}
