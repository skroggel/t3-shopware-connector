<?php
defined('TYPO3') or die();

call_user_func(
    function (string $extensionKey) {

              //=================================================================
        // Register Cache
        //=================================================================
        if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shopwareconnector_apicalls'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shopwareconnector_apicalls'] = [];
        }
        if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shopwareconnector_apicalls']['backend'])) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['shopwareconnector_apicalls']['backend'] = \TYPO3\CMS\Core\Cache\Backend\FileBackend::class;
        }

        //=================================================================
        // Add form configuration
        //=================================================================
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            $extensionKey,
            'setup',
            'module.tx_form {
                settings {
                    yamlConfigurations {
                        1732885676 = EXT:' . $extensionKey . '/Configuration/Yaml/FormSetup.yaml
                    }
                }
            }
            plugin.tx_form {
                settings {
                    yamlConfigurations {
                        1732885676 = EXT:' . $extensionKey . '/Configuration/Yaml/FormSetup.yaml
                    }
                }
            }'
        );

        //=================================================================
        // Add Plugins
        //=================================================================
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            $extensionKey,
            'ApiRequest',
            [
                \Madj2k\ShopwareConnector\Controller\ApiRequestController::class => 'list,show,download,downloadExecute'
            ],

            // non-cacheable actions
            [
            ],
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

        //=================================================================
        // cHash
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_shopwareconnector_apirequest[slug]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_shopwareconnector_apirequest[productNumber]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_shopwareconnector_apirequest[productId]';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'tx_shopwareconnector_apirequest[term]';

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
