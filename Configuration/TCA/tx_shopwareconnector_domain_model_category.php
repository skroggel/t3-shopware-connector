<?php
$ll = 'LLL:EXT:shopware_connector/Resources/Private/Language/locallang_db.xlf:';
return [
    'ctrl' => [
        'title' => $ll . 'tx_shopwareconnector_domain_model_category',
        'label' => 'name',
        'label_alt' => 'slug',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'languageField' => 'sw_language_id',
        'transOrigPointerField' => 'parent_uid',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'versioningWS' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name,description,slug',
        'iconfile' => 'EXT:shopware_connector/Resources/Public/Icons/tx_shopwareconnector_domain_model_category.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                name, slug, --palette--;;visibility,
                --div--;' . $ll . 'tabs.description, description,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sw_language_id, parent_uid, l10n_diffsource,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime
            ',
        ],
    ],
    'palettes' => [
        'visibility' => [
            'showitem' => 'hide_in_menu',
            'label' => $ll . 'palette.visibility',
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    ['LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.enabled', 1],
                ],
            ],
        ],
        'name' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'slug' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.slug',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'description' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'sw_language_id' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.sw_language_id',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'parent_uid' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.parent_uid',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_shopwareconnector_domain_model_category',
                'maxitems' => 1,
            ],
        ],
        'hide_in_menu' => [
            'exclude' => 1,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.hide_in_menu',
            'config' => [
                'type' => 'check',
                'items' => [
                    ['LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.no', 1],
                ],
            ],
        ],
    ],
];
