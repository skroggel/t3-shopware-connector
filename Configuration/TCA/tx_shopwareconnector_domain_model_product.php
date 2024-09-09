<?php
$ll = 'LLL:EXT:shopware_connector/Resources/Private/Language/locallang_db.xlf:';
return [
    'ctrl' => [
        'title' => $ll . 'tx_shopwareconnector_domain_model_product',
        'label' => 'name',
        'label_alt' => 'product_number',
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
        ],
        'searchFields' => 'name, product_number, ean, tags',
        'iconfile' => 'EXT:shopware_connector/Resources/Public/Icons/tx_shopwareconnector_domain_model_product.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --palette--;;product_info,
                --palette--;;price_info,
                --palette--;;stock_info,
                --palette--;;dimension_info,
                --palette--;;media_info,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sw_language_id, parent_uid, l10n_diffsource,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, crdate, tstamp
            ',
        ],
    ],
    'palettes' => [
        'product_info' => [
            'showitem' => 'product_number, name, ean, is_new, custom_fields, release_date',
            'label' => $ll . 'palette.product_info',
        ],
        'price_info' => [
            'showitem' => 'price, calculated_price',
            'label' => $ll . 'palette.price_info',
        ],
        'stock_info' => [
            'showitem' => 'available_stock, stock, available, restock_time, shipping_free, delivery_time, purchase_steps, max_purchase, min_purchase, purchase_unit, pack_unit, pack_unit_plural',
            'label' => $ll . 'palette.stock_info',
        ],
        'dimension_info' => [
            'showitem' => 'weight, width, height, length',
            'label' => $ll . 'palette.dimension_info',
        ],
        'media_info' => [
            'showitem' => 'tags, description, cover, cover_mime_type, meta_title, meta_description, meta_keywords',
            'label' => $ll . 'palette.media_info',
        ],
    ],
    'columns' => [
        'product_number' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.product_number',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'name' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'ean' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.ean',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'is_new' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.is_new',
            'config' => [
                'type' => 'check',
            ],
        ],
        'custom_fields' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.custom_fields',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
            ],
        ],
        'release_date' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.release_date',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'price' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.price',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2',
                'default' => '0.00',
            ],
        ],
        'calculated_price' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.calculated_price',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
            ],
        ],
        'available_stock' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.available_stock',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'stock' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.stock',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'available' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.available',
            'config' => [
                'type' => 'check',
            ],
        ],
        'restock_time' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.restock_time',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'shipping_free' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.shipping_free',
            'config' => [
                'type' => 'check',
            ],
        ],
        'delivery_time' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.delivery_time',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
            ],
        ],
        'purchase_steps' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.purchase_steps',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'max_purchase' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.max_purchase',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'min_purchase' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.min_purchase',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'purchase_unit' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.purchase_unit',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'pack_unit' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.pack_unit',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'pack_unit_plural' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.pack_unit_plural',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'weight' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.weight',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2',
                'default' => '0.00',
            ],
        ],
        'width' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.width',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2',
                'default' => '0.00',
            ],
        ],
        'height' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.height',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2',
                'default' => '0.00',
            ],
        ],
        'length' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.length',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2',
                'default' => '0.00',
            ],
        ],
        'tags' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.tags',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
            ],
        ],
        'description' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'cover' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.cover',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'cover_mime_type' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.cover_mime_type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'meta_title' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.meta_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'meta_description' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.meta_description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'meta_keywords' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.meta_keywords',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
    ],
];
