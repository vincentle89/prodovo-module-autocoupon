<?php
/**
 * Imageine Online Auto Coupon
 * Copyright (C) 2020  Imageine Online
 *
 * This file is part of Imageineonline/AutoCoupon.
 *
 * Imageineonline/AutoCoupon is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Imageineonline\AutoCoupon\Observer\Frontend;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;

class ApplyCoupon implements ObserverInterface
{

    const XML_MODULE_STATUS = 'ioauto_coupon/general/status';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var Session
     */
    protected $checkoutSession;
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * AdminFailed constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     * @param RequestInterface $request
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        RequestInterface $request
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $couponCode = $this->checkoutSession->getQuote()->getCouponCode();

        if (0 > strlen($couponCode)) {
            $this->checkoutSession->getQuote()->setCouponCode($couponCode)
                ->collectTotals()
                ->save();
        }
    }

    /**
     * Module Status
     *
     * @return mixed
     */
    public function isEnabled()
    {
        $storeScope = ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::XML_MODULE_STATUS, $storeScope);
    }
}
