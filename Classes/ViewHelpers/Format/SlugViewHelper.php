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
namespace Madj2k\ShopwareConnector\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to generate a URL-friendly slug from a string.
 *
 * Usage in Fluid:
 *   <f:format.slug>{stringToSlugify}</f:format.slug>
 *   {f:format.slug(string: 'Turnschuh Coral')}
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SlugViewHelper extends AbstractViewHelper
{

    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('string', 'string', 'String to slugify', false, '');
        $this->registerArgument('lowercase', 'bool', 'Force lowercase output', false, true);
        $this->registerArgument('allowUnicode', 'bool', 'Keep unicode instead of ASCII transliteration', false, false);
        $this->registerArgument('replacement', 'string', 'Separator character', false, '-');
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $value = $this->arguments['string'] ?: $this->renderChildren();
        if ($value === null || $value === '') {
            return '';
        }
        $value = (string)$value;

        $replacement = (string)$this->arguments['replacement'];
        $lowercase   = (bool)$this->arguments['lowercase'];
        $allowUnicode = (bool)$this->arguments['allowUnicode'];

        // Optional ASCII transliteration before processing
        if (!$allowUnicode) {
            $trans = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
            if ($trans !== false) {
                $value = $trans;
            }
        }

        // Try TYPO3 SlugHelper if available
        if (class_exists(\TYPO3\CMS\Core\DataHandling\SlugHelper::class)) {
            /** @var \TYPO3\CMS\Core\DataHandling\SlugHelper $helper */
            $helper = GeneralUtility::makeInstance(
                \TYPO3\CMS\Core\DataHandling\SlugHelper::class,
                '_dummyTable',
                '_dummyField',
                [] // use default configuration
            );
            $slug = $helper->sanitize($value);
        } else {
            // Minimal fallback slugify
            $slug = preg_replace('~[^\pL\d]+~u', $replacement, $value);
            $slug = trim((string)$slug, $replacement);
            $slug = preg_replace('~[^-\w]+~', '', (string)$slug);
        }

        // Normalize multiple separators
        $quotedSep = preg_quote($replacement, '~');
        $slug = preg_replace('~' . $quotedSep . '+~', $replacement, (string)$slug);

        if ($lowercase) {
            $slug = mb_strtolower((string)$slug, 'UTF-8');
        }

        return $slug ?: 'n-a';
    }
}
