<?php

declare(strict_types=1);

/*
 * This file is part of the InspiredMinds_BelboonTracking Magento 2 module.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\BelboonTracking\Controller\StoreClickId;

class Index extends \Magento\Framework\App\Action\Action
{
    public const SESSION_PARAM = 'belboonClickId';

    protected $resultRawFactory;
    protected $trackingHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \InspiredMinds\BelboonTracking\Helper\BelboonTrackingHelper $trackingHelper
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->trackingHelper = $trackingHelper;
    }

    public function execute(): \Magento\Framework\Controller\ResultInterface
    {
        if (null !== ($clickId = $this->getRequest()->getParam('clickId'))) {
            $this->trackingHelper->storeClickId($clickId);
        }

        /** @var \Magento\Framework\Controller\Result\Raw $result */
        $result = $this->resultRawFactory->create();
        $result->setHttpResponseCode(204);
        $result->setContents('');

        return $result;
    }
}
