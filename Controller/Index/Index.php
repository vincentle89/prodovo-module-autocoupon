<?php declare(strict_types=1);
/**
 * A Magento 2 module named Imageineonline/AutoCoupon
 * Copyright (C) 2020 Imageine Online
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

namespace Imageineonline\AutoCoupon\Controller\Index;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

class Index extends \Magento\Framework\App\Action\Action
{

	const XML_MODULE_STATUS = 'ioauto_coupon/general/status';
	const XML_MODULE_MESSAGE = 'ioauto_coupon/general/message';
	const XML_MODULE_ERROR_MESSAGE = 'ioauto_coupon/general/error_message';

	/**
	 * @var ScopeConfigInterface
	 */
	protected $scopeConfig;
	/**
	 * @var \Magento\Checkout\Model\Session
	 */
	protected $checkoutSession;
	/**
	 * @var RequestInterface
	 */
	protected $request;

	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $resultPageFactory;

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;

	/**
	 * @var \Magento\Framework\Message\ManagerInterface
	 */
	protected $messageManager;

	/**
	 * Constructor
	 *
	 * @param \Magento\Framework\App\Action\Context  $context
	 * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 */
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Message\ManagerInterface $messageManager,
        ScopeConfigInterface $scopeConfig,
		\Magento\Checkout\Model\Session $checkoutSession,
        RequestInterface $request
	) {
		$this->resultPageFactory = $resultPageFactory;
		$this->_storeManager = $storeManager;
		$this->messageManager = $messageManager;
		$this->scopeConfig = $scopeConfig;
		$this->checkoutSession = $checkoutSession;
		$this->request = $request;
		parent::__construct($context);
	}

	/**
	 * Execute view action
	 *
	 * @return \Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{

		if ($this->isEnabled()){

			$couponCode = $this->getRequest()->getParam('code');

			$redirectURL = $this->getRequest()->getParam('redirect_url');
			/* get clean base url */
			$baseURL = str_replace('http://','', $this->_storeManager->getStore()->getBaseUrl());
			$baseURL = str_replace('https://','', $this->_storeManager->getStore()->getBaseUrl());
			$baseURL = str_replace('/','', $baseURL);

			if ($couponCode) {

				$coupon = $this->checkoutSession->getQuote()->setCouponCode($couponCode)
				                      ->collectTotals()
				                      ->save();
				if($coupon->getCouponCode()) {
					// Successful Application
					$this->messageManager->addSuccess( $this->getCustomMessage() );
				}else{
					// Failed Application
					$this->messageManager->addErrorMessage( $this->getCustomErrorMessage() );
				}
			}else{
				$this->checkoutSession->getQuote()->setCouponCode('')
				                      ->collectTotals()
				                      ->save();
			}

			if($redirectURL) {

                $parsed = parse_url($redirectURL);
                if (empty($parsed['scheme'])) {
                    header("Location: " . "http://" . $redirectURL);
                }else {
                    header("Location: " . $redirectURL);
                }
                exit();
			}else{
                $this->_redirect("/");
            }
		}
		else{
			$this->_redirect("/");
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

	/**
	 * Module Config Message
	 *
	 * @return mixed
	 */
	public function getCustomMessage() {
		$storeScope = ScopeInterface::SCOPE_STORE;
		return $this->scopeConfig->getValue(self::XML_MODULE_MESSAGE, $storeScope);
	}


	/**
	 * Module Config Error Message
	 *
	 * @return mixed
	 */
	public function getCustomErrorMessage() {
		$storeScope = ScopeInterface::SCOPE_STORE;
		return $this->scopeConfig->getValue(self::XML_MODULE_ERROR_MESSAGE, $storeScope);
	}
}