<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\AdminAdobeIms\Plugin;

use Magento\AdminAdobeIms\Service\ImsConfig;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Integration\Model\AdminTokenService;

class AdminTokenPlugin
{
    /** @var ImsConfig */
    private ImsConfig $imsConfig;

    /**
     * @param ImsConfig $imsConfig
     */
    public function __construct(
        ImsConfig $imsConfig
    ) {
        $this->imsConfig = $imsConfig;
    }

    /**
     * Disable generation of admin token if AdminAdobeIms module is enabled
     *
     * @param AdminTokenService $subject
     * @param callable $proceed
     * @param string $username
     * @param string $password
     * @return mixed
     * @throws AuthenticationException
     */
    public function aroundCreateAdminAccessToken(AdminTokenService $subject, callable $proceed, $username, $password)
    {
        if (!$this->imsConfig->enabled()) {
            return $proceed($username, $password);
        }

        throw new AuthenticationException(
            __(
                'Admin token generation is disabled. Please use Adobe IMS ACCESS_TOKEN.'
            )
        );
    }
}
