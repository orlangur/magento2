<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Customer\Block;

/**
 * Customer front  newsletter manage block
 *
 * @api
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Newsletter extends \Magento\Customer\Block\Account\Dashboard
{
    /**
     * @var string
     */
    protected $_template = 'form/newsletter.phtml';

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsSubscribed()
    {
        return $this->getSubscriptionObject()->isSubscribed();
    }

    /**
     * Return the save action Url.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl('newsletter/manage/save');
    }
}
