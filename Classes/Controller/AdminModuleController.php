<?php
declare(strict_types=1);

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or (at your option) any later version.
 *
 * The TYPO3 project - inspiring people to share!
 */
namespace Madj2k\ShopwareConnector\Controller;


use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class AdminModuleController
 **
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AdminModuleController extends ActionController
{
    /**
     * @const string
     */
    const REGISTRY_NAMESPACE = 'shopware_connector';


    /**
     * @const string
     */
    const REGISTRY_KEY = 'shopwareConfig';


    /**
     * @var \TYPO3\CMS\Core\Registry
     */
    protected Registry $registry;


    /**
     * BackendModuleController constructor.
     */
    public function __construct()
    {
        $this->registry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Registry::class);
    }


    /**
     * Renders the form for managing API credentials.
     * Loads the current API credentials from the registry and displays them in the form.
     *
     * @param array $shopwareConfig
     * @return void
     */
    public function indexAction(array $shopwareConfig = []): void
    {

        if (! $shopwareConfig) {
            $shopwareConfig = $this->registry->get(
                self::REGISTRY_NAMESPACE,
                self::REGISTRY_KEY,
            );
        }

        $this->view->assignMultiple([
            'shopwareConfig' => $shopwareConfig
        ]);
    }


    /**
     * Saves the API credentials to the TYPO3 Core registry.
     *
     * @param array $shopwareConfig
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function saveAction(array $shopwareConfig = []): void
    {
        if (
            ($shopwareConfig['apiUrl'])
            && ($shopwareConfig['apiKey'])
        ) {

            $this->registry->set(
                self::REGISTRY_NAMESPACE,
                self::REGISTRY_KEY,
                $shopwareConfig
            );

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'adminModuleController.message.saved',
                    'shopware_connector'
                ),
            );

        } else {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'adminModuleController.error.valuesNotSet',
                    'shopware_connector'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
        }

        $this->redirect(
            'index',
            null,
            null,
            ['shopwareConfig' => $shopwareConfig]
        );
    }
}
