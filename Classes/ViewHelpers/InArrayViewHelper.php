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
namespace Madj2k\ShopwareConnector\ViewHelpers;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class InArrayViewHelper
 *
 * Checks if a given needle is contained in a given haystack (array or \Traversable).
 * Useful in Fluid to emulate PHP's in_array() – especially for checkbox preselection.
 *
 * Example:
 *   {vh:inArray(needle: value, haystack: selectedValues)}  // returns boolean
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class InArrayViewHelper extends AbstractViewHelper
{
    /**
     * Disable HTML escaping – we return a boolean only.
     *
     * @var bool
     */
    protected $escapeOutput = false;


    /**
     * Disable children rendering – not needed here.
     *
     * @var bool
     */
    protected $escapeChildren = false;


    /**
     * Registers the ViewHelper arguments.
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(
            'needle',
            'mixed',
            'The value to search for (strict comparison by default).',
            true
        );

        $this->registerArgument(
            'haystack',
            'array',
            'The array or Traversable to search in.',
            true
        );

        $this->registerArgument(
            'strict',
            'bool',
            'Whether to use strict comparison (===).',
            false,
            true
        );

    }


    /**
     * Executes the check equivalent to PHP's in_array().
     *
     * @return bool Returns TRUE if the needle exists in haystack, otherwise FALSE.
     */
    public function render(): bool
    {
        /** @var mixed $needle */
        $needle = $this->arguments['needle'];

        /** @var mixed $haystackRaw */
        $haystack = $this->arguments['haystack'];

        /** @var bool $strict */
        $strict = (bool)$this->arguments['strict'];

        return in_array($needle, $haystack, $strict);
    }
}
