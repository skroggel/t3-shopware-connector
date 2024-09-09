<?php
defined('TYPO3') or die();

call_user_func(
    function (string $extensionKey) {

        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'Madj2k.' . $extensionKey,
                'web',
                'shopware',
                '',
                [
                    \Madj2k\ShopwareConnector\Controller\AdminModuleController::class => 'index,save',
                ],
                [
                    'access' => 'user,group',
                    'icon' => 'EXT:' . $extensionKey . '/Resources/Public/Icons/module-icon.svg',
                    'labels' => 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang_shopware.xlf',
                ]
            );
        }
    },
    'shopware_connector'
);
