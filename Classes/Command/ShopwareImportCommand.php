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
namespace Madj2k\ShopwareConnector\Command;

use Madj2k\ShopwareConnector\Service\ShopwareImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ShopwareImportCommand
 *
 * Call: 'vendor/bin/typo3 shopware:import'
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ShopwareImportCommand extends Command
{
    /**
     * @var \Madj2k\ShopwareConnector\Service\ShopwareImporter
     */
    private ShopwareImporter $importer;


    /**
     * @param ShopwareImporter $importer
     */
    public function __construct(ShopwareImporter $importer)
    {
        parent::__construct();
        $this->importer = $importer;
    }


    /**
     * Configures the command with a description and help text.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Imports categories and products from Shopware.')
            ->addArgument(
                'swLanguageId',
                InputArgument::REQUIRED,
                'The sw-language-id for the import.',
                // '0191c7b547e37dbfab666ce0f50424e7'
            )
            ->setHelp('This command allows you to import categories and products from Shopware into TYPO3.');

    }


    /**
     * Executes the import process.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int Returns Command::SUCCESS on success, Command::FAILURE on failure.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            $swLanguageId = $input->getArgument('swLanguageId');

            $this->importer->setSwLanguageId($swLanguageId);
            $importSuccess = $this->importer->executeImport();

            if ($importSuccess) {
                $output->writeln('Import completed successfully.');
                return Command::SUCCESS;
            } else {
                $output->writeln('<error>Import completed with issues. Check the logs for more details.</error>');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $output->writeln('<error>Import failed: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
