<?php
defined('TYPO3') or die('Access denied.');
call_user_func(
    function (string $extKey) {

        $pluginConfig = ['api_request'];
        foreach ($pluginConfig as $pluginName) {

            $typo3Version = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
            $version = $typo3Version->getMajorVersion();

            $iconIdentifier =  strtolower(TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToLowerCamelCase($extKey)) .
                '-plugin-' .  strtolower(TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToLowerCamelCase($pluginName));

            // register normal plugin
            $pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
                $extKey,
                \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($pluginName),
                'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:plugin.' .
                    \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToLowerCamelCase($pluginName) . '.title',
                $iconIdentifier,
                TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($extKey),
                'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:plugin.' .
                    TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToLowerCamelCase($pluginName) . '.description'
            );


            // add flexform to plugin
            $flexFormFile = 'FILE:EXT:' . $extKey . '/Configuration/FlexForms/' .
                \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($pluginName) . '.xml';

            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
                '*', // wildcard when using third parameter, else use pluginSignature
                $flexFormFile,
                $pluginSignature // third parameter adds flexform to content-element below, too!
            );

            // define TCA-fields
            // $GLOBALS['TCA']['tt_content']['types'][$pluginSignature] = $GLOBALS['TCA']['tt_content']['types']['list'];
            $GLOBALS['TCA']['tt_content']['types'][$pluginSignature]['showitem'] = '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,
                    pi_flexform,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ';

        }
    },
    'shopware_connector'
);
