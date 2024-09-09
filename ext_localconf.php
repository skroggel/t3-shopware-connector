<?php
defined('TYPO3') or die();

call_user_func(
    function (string $extensionKey) {

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            $extensionKey,
            'constants',
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $extensionKey . '/Configuration/TypoScript/constants.typoscript">'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            $extensionKey,
            'setup',
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $extensionKey . '/Configuration/TypoScript/setup.typoscript">'
        );

        //=================================================================
        // Register Cache
        //=================================================================
        if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shopwareconnector_apicalls'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shopwareconnector_apicalls'] = [];
        }
        if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shopwareconnector_apicalls']['backend'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shopwareconnector_apicalls']['backend'] = \TYPO3\CMS\Core\Cache\Backend\FileBackend::class;
        }

        //=================================================================
        // Register Logger
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['LOG']['Madj2k']['ShopwareConnector']['writerConfiguration'] = [

            // configuration for WARNING severity, including all
            // levels with higher severity (ERROR, CRITICAL, EMERGENCY)
            \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
                // add a FileWriter
                'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => [
                    // configuration for the writer
                    'logFile' => \TYPO3\CMS\Core\Core\Environment::getVarPath()  . '/log/tx_shopwareconnector.log'
                ]
            ]
        ];

    },
    'shopware_connector'
);
