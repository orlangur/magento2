<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Theme\Model\Theme\Plugin;

use Magento\Backend\App\AbstractAction;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Config\Theme;
use Magento\Framework\Exception\LocalizedException;
use Magento\Theme\Model\ResourceModel\Theme\Collection as ThemeLoader;
use Magento\Theme\Model\Theme\Collection as ThemeCollection;
use Magento\Theme\Model\Theme\Registration as ThemeRegistration;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Registration
{
    /** @var ThemeRegistration */
    protected $themeRegistration;

    /** @var ThemeCollection */
    protected $themeCollection;

    /** @var ThemeLoader */
    protected $themeLoader;

    /** @var LoggerInterface */
    protected $logger;

    /** @var AppState */
    protected $appState;

    /**
     * @param ThemeRegistration $themeRegistration
     * @param ThemeCollection $themeCollection
     * @param ThemeLoader $themeLoader
     * @param LoggerInterface $logger
     * @param AppState $appState
     */
    public function __construct(
        ThemeRegistration $themeRegistration,
        ThemeCollection $themeCollection,
        ThemeLoader $themeLoader,
        LoggerInterface $logger,
        AppState $appState
    ) {
        $this->themeRegistration = $themeRegistration;
        $this->themeCollection = $themeCollection;
        $this->themeLoader = $themeLoader;
        $this->logger = $logger;
        $this->appState = $appState;
    }

    /**
     * Add new theme from filesystem and update existing
     *
     * @param AbstractAction $subject
     * @param RequestInterface $request
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeDispatch(
        AbstractAction $subject,
        RequestInterface $request
    ) {
        try {
            if ($this->appState->getMode() != AppState::MODE_PRODUCTION) {
                $this->themeRegistration->register();
                $this->updateThemeData();
            }
        } catch (LocalizedException $e) {
            $this->logger->critical($e);
        }
    }

    /**
     * Update theme data
     *
     * @return void
     */
    protected function updateThemeData()
    {
        $themesFromConfig = $this->themeCollection->loadData();
        /** @var \Magento\Theme\Model\Theme $themeFromConfig */
        foreach ($themesFromConfig as $themeFromConfig) {
            /** @var \Magento\Theme\Model\Theme $themeFromDb */
            $themeFromDb = $this->themeLoader->getThemeByFullPath(
                $themeFromConfig->getArea()
                . Theme::THEME_PATH_SEPARATOR
                . $themeFromConfig->getThemePath()
            );

            if ($themeFromConfig->getParentTheme()) {
                $parentThemeFromDb = $this->themeLoader->getThemeByFullPath(
                    $themeFromConfig->getParentTheme()->getFullPath()
                );
                $themeFromDb->setParentId($parentThemeFromDb->getId());
            }

            $themeFromDb->setThemeTitle($themeFromConfig->getThemeTitle());
            $themeFromDb->save();
        }
    }
}
