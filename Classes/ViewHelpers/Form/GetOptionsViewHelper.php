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
namespace Madj2k\ShopwareConnector\ViewHelpers\Form;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class GetSelectOptionsViewHelper
 *
 * Prepares a flat options array from an API response to be used in a <f:form.select> element.
 * The result is an associative array where the key is the option ID and the value is the translated name.
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class GetOptionsViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument(
            'options',
            'array',
            'The options array from the API response',
            true
        );
    }

    /**
     * Render method
     *
     * @return array<string, string>
     */
    public function render(): array
    {
        /** @var array $options */
        $options = $this->arguments['options'];

        /** @var array<string, string> $result */
        $result = [];

        foreach ($options as $option) {
            if (
                isset($option['id'], $option['translated']['name']) &&
                is_string($option['id']) &&
                is_string($option['translated']['name'])
            ) {
                $result[$option['id']] = $option['translated']['name'];
            }
        }

        return $result;
    }
}
