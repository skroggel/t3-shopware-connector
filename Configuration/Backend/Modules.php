<?php

declare(strict_types=1);

return [
    'web_shopwareconnector' => [
        'parent' => 'tools',
        'position' => ['after' => 'tools_csp'],
        'access' => 'admin',
        'icon' => 'EXT:shopware_connector/Resources/Public/Icons/module-icon.svg',
        'labels' => 'LLL:EXT:shopware_connector/Resources/Private/Language/locallang_shopware.xlf',
        'path' => '/module/web/shopwareconnector',
        'extensionName' => 'shopware_connector',
        'controllerActions' => [
            \Madj2k\ShopwareConnector\Controller\AdminModuleController::class=> [
                'index',
                'save'
            ],
        ],
    ],
];
