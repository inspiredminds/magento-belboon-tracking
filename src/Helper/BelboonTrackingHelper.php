<?php

declare(strict_types=1);

/*
 * This file is part of the InspiredMinds_BelboonTracking Magento 2 module.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\BelboonTracking\Helper;

class BelboonTrackingHelper
{
    public const SESSION_PARAM = 'belboonClickId';

    private $session;
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     ) {
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(
            'inspiredminds_belboontracking/general/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function storeClickId(string $clickId): void
    {
        $this->session->setData(self::SESSION_PARAM, $clickId);
    }

    public function getClickId(): ?string
    {
        return $this->session->getData(self::SESSION_PARAM);
    }

    public function removeClickId(): void
    {
        $this->session->setData(self::SESSION_PARAM, null);
    }

    public function getDomain(): ?string
    {
        return $this->scopeConfig->getValue(
            'inspiredminds_belboontracking/general/domain',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getProgramId(): ?string
    {
        return $this->scopeConfig->getValue(
            'inspiredminds_belboontracking/general/programid',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
