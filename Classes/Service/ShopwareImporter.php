<?php
declare(strict_types=1);
namespace Madj2k\ShopwareConnector\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or (at your option) any later version.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Madj2k\ShopwareConnector\Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ShopwareImporter
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ShopwareImporter implements LoggerAwareInterface
{
    use LoggerAwareTrait;


    /**
     * @const string
     */
    protected const YAML_CONFIG = 'EXT:shopware_connector/Configuration/ShopwareImporter/FieldMapping.yaml';


    /**
     * @var ShopwareApiService
     */
    protected ShopwareApiService $shopwareApiService;


    /**
     * @var array
     */
    protected array $mappings = [];


    /**
     * @var string
     */
    protected string $swLanguageId = '';


    /**
     * @var int
     */
    protected int $pid = 0;


    /**
     * Constructor.
     *
     * @param ShopwareApiService $shopwareApiService
     */
    public function __construct(ShopwareApiService $shopwareApiService)
    {
        $this->shopwareApiService = $shopwareApiService;
        $this->loadYamlMappings();
    }


    /**
     * Loads the YAML mappings from the configuration folder.
     */
    protected function loadYamlMappings(): void
    {
        $mappingFile = GeneralUtility::getFileAbsFileName(self::YAML_CONFIG);
        $this->mappings = Yaml::parseFile($mappingFile);
    }


    /**
     * Returns the specific mapping configuration for an entity.
     *
     * @param string $entityType
     * @return array
     * @throws \Madj2k\ShopwareConnector\Exception
     */
    protected function getMappingConfig(string $entityType): array
    {
        if (
            (! $this->mappings[$entityType])
            || (! is_array($this->mappings[$entityType]))
        ){
            throw new Exception(
                sprintf(
                    'Could not load mapping for type "%s".',
                    $entityType
                ),
                1725887298
            );
        }

        return $this->mappings[$entityType];
    }


    /**
     * @return string
     */
    public function getSwLanguageId(): string
    {
        return $this->swLanguageId;
    }


    /**
     * @param string $swLanguageId
     * @return void
     */
    public function setSwLanguageId(string $swLanguageId): void
    {
        $this->swLanguageId = $swLanguageId;
    }


    /**
     * @return int
     */
    public function getPid(): int
    {
        return $this->pid;
    }


    /**
     * @param int $pid
     * @return void
     */
    public function setPid(int $pid): void
    {
        $this->pid = $pid;
    }


    /**
     * Executes the import process for all entities.
     *
     * @param string $entityType
     * @return bool
     */
    public function executeImport(string $entityType = 'product'): bool
    {
        return $this->importEntities($entityType);
    }


    /**
     * Imports entities based on the entity type (e.g., product, category).
     *
     * @param string $entityType
     * @return bool
     */
    protected function importEntities(string $entityType): bool
    {
        try {

            $mapping = $this->getMappingConfig($entityType);

            // Build the 'include' and 'association' parameters from the mapping
            $includeFields = $this->buildIncludes($mapping);
            $associationParams = $this->buildAssociations($mapping);

            // Fetch the entities from the API with the include and association params
            $entities = $this->shopwareApiService->fetchFromApi($mapping['apiEndpoint'],
                [
                    'includes' => $includeFields,
                    'associations' => $associationParams
                ],
                $this->getSwLanguageId()
            );

            // Iterate through the entities and process them
            foreach ($entities['elements'] as $entityData) {
                $entityUid = $this->updateOrInsertEntity($entityType, $entityData);
                $this->importAssociations($entityType, $entityData, $entityUid);
            }

            return true;

        } catch (\Throwable $e) {

            $this->logger->error(
                sprintf(
                    'Error importing %s: %s',
                    $entityType,
                    $e->getMessage()
                )
            );
            return false;
        }
    }


    /**
     * @param array $mapping
     * @return array
     */
    protected function buildIncludes(array $mapping): array
    {
        $includeFields = [];

        // Add fields from the main mapping
        if (isset($mapping['fields'])) {
            foreach ($mapping['fields'] as $config) {
                if ($config['type'] !== 'none' && isset($config['apiField'])) {
                    $includeFields[] = $config['apiField'];
                }
            }
        }

        // Add fields from associations
        if (isset($mapping['associations'])) {
            foreach ($mapping['associations'] as $associationConfig) {
                if (isset($associationConfig['apiField'])) {
                    $includeFields[] = $associationConfig['apiField'];
                }
            }
        }

        return array_unique($includeFields);
    }


    /**
     * @param array $mapping
     * @return array
     */
    protected function buildAssociations(array $mapping): array
    {
        $associationParams = [];

        if (isset($mapping['associations'])) {
            foreach ($mapping['associations'] as $associationConfig) {
                $totalCountMode = $associationConfig['totalCountMode'] ?? 1;
                $associationParams[$associationConfig['apiField']] = [
                    'total-count-mode' => $totalCountMode,
                ];
            }
        }

        return $associationParams;
    }


    /**
     * Updates or inserts an entity into the database.
     *
     * @param string $entityType
     * @param array $entityData
     * @return int|null Returns the UID of the inserted or updated entity.
     * @throws \Throwable
     */
    protected function updateOrInsertEntity(string $entityType, array $entityData): ?int
    {
        $mappingConfig = $this->getMappingConfig($entityType);
        $table = $mappingConfig['table'];
        $uniqueKeyField = $mappingConfig['uniqueKeyField'] ?? 'sw_id';
        $uniqueLanguageField = $mappingConfig['uniqueLanguageField'] ?? 'sw_language_id';

        if (!$table){
            throw new Exception(
                sprintf(
                    'Missing table parameter for type "%s".',
                    $entityType
                ),
                1725689184
            );
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        try {
            $connection->beginTransaction();
            $mappedData = $this->mapData($entityData, $mappingConfig);

            $existingEntity = $connection->select(
                ['uid', 'checksum'],
                $table,
                [
                    $uniqueKeyField => $mappedData[$uniqueKeyField],
                    $uniqueLanguageField => $this->getSwLanguageId()
                ]
            )->fetchAssociative();

            if ($existingEntity) {

                $entityUid = (int)$existingEntity['uid'];
                if ($existingEntity['checksum'] !== $mappedData['checksum']) {
                    $connection->update(
                        $table,
                        $mappedData,
                        [
                            'uid' => $entityUid
                        ]
                    );
                }

            } else {
                $connection->insert(
                    $table,
                    $mappedData
                );
                $entityUid = (int)$connection->lastInsertId();
            }

            $connection->commit();
            return $entityUid;

        } catch (\Throwable $e) {

            $connection->rollBack();
            $this->logger->error(
                sprintf(
                    'Error updating or inserting %s: %s',
                    $entityType,
                    $e->getMessage()
                )
            );
            throw $e;
        }
    }


    /**
     * Imports associations defined in the YAML mapping.
     *
     * @param string $entityType
     * @param array $data
     * @param int $entityUid
     * @throws \Throwable
     */
    protected function importAssociations(string $entityType, array $data, int $entityUid): void
    {
        $mappingConfig = $this->getMappingConfig($entityType);
        foreach ($mappingConfig['associations'] as $associationKey => $associationConfig) {

            $associationData = [];
            if (isset($associationConfig['apiField'])) {
                $associationData = $data[$associationConfig['apiField']] ?? null;
            }

            if (!$associationData) {
                $this->logger->debug(
                    sprintf(
                        'No data to process for association %s',
                        $associationKey
                    )
                );
                continue;
            }

            if ($associationConfig['mmTable'] ?? false) {
                // Handle many-to-many associations
                $this->importManyToMany($associationConfig, $associationData, $entityType, $entityUid);
            } else {
                // Handle one-to-many associations
                $this->importOneToMany($associationConfig, $associationData, $entityType, $entityUid);
            }
        }
    }


    /**
     * Imports many-to-many associations, such as product categories or tags.
     *
     * @param array $associationConfig
     * @param array $associationData
     * @param string $entityType
     * @param int $entityUid
     * @return void
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    protected function importManyToMany(
        array $associationConfig,
        array $associationData,
        string $entityType,
        int $entityUid
    ): void {

        $referenceEntityType = $associationConfig['reference'];
        $mmTable = $associationConfig['mmTable'];

        $localMappingConfig = $this->getMappingConfig($entityType);
        $localTable = $localMappingConfig['table'];

        $localField = $associationConfig['localField'];
        $foreignField = $associationConfig['foreignField'];

        if (!$localTable || !$localField  || !$foreignField || !$mmTable || !$referenceEntityType){
            throw new Exception(
                sprintf(
                    'Missing configuration parameters. Can not import many-to-many associations for type "%s".',
                    $entityType
                ),
                1725689182
            );
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($mmTable);

        try {
            $connection->beginTransaction();

            // delete existing references for the given entity
            $connection->delete($mmTable, ['uid_local' => $entityUid]);

            foreach ($associationData as $associationDataItem) {

                // update or insert
                if ($foreignUid = $this->updateOrInsertEntity($referenceEntityType, $associationDataItem)) {

                    // now set mm-relation
                    $mappedData = [
                        'uid_local' => $entityUid,
                        'uid_foreign' => $foreignUid
                    ];
                    $connection->insert($mmTable, $mappedData);

                } else {
                    throw new Exception('Could not import entity.', 1725887717);
                }
            }

            // Calculate sum of relationships for local table
            $localCount = $connection->count('*', $mmTable, ['uid_local' => $entityUid]);
            $this->updateManyToManyCounter($localTable, $entityUid, $localField, $localCount);

            $connection->commit();

        } catch (\Throwable $e) {

            $connection->rollBack();
            $this->logger->error(
                sprintf(
                    'Error while processing association from %s to type %s via MM-table %s for %s: %s',
                    $localTable,
                    $referenceEntityType,
                    $mmTable,
                    $entityUid,
                    $e->getMessage()
                )
            );
            throw $e;
        }
    }


    /**
     * Update the sum field of a given entity.
     *
     * @param string $table
     * @param int $entityUid
     * @param string $sumField
     * @param int $count
     * @return void
     * @internal
     */
    protected function updateManyToManyCounter(string $table, int $entityUid, string $sumField, int $count): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($table);
        $connection->update(
            $table,
            [$sumField => $count],
            ['uid' => $entityUid]
        );
    }


    /**
     * Imports one-to-many associations, such as properties.
     *
     * @param array $associationConfig
     * @param array $associationData
     * @param string $entityType
     * @param int $entityUid
     * @return void
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Madj2k\ShopwareConnector\Exception
     * @throws \Throwable
     */
    protected function importOneToMany(
        array $associationConfig,
        array $associationData,
        string $entityType,
        int $entityUid
    ): void {

        $reference = $associationConfig['reference'];
        $localField = $associationConfig['localField'] ?? null;
        $foreignField = $associationConfig['foreignField'] ?? null;

        if ((!$localField && !$foreignField) || !$reference){
            throw new Exception(
                sprintf(
                    'Missing configuration parameters. Can not import one-to-many associations for type "%s".',
                    $entityType
                ),
                1725689183
            );
        }

        $localMappingConfig = $this->getMappingConfig($entityType);
        $localTable = $localMappingConfig['table'];

        $foreignMappingConfig = $this->getMappingConfig($reference);
        $foreignTable = $foreignMappingConfig['table'];

        $uniqueKeyField = $foreignMappingConfig['uniqueKeyField'] ?? 'sw_id';
        $uniqueLanguageField = $foreignMappingConfig['uniqueLanguageField'] ?? 'sw_language_id';

        if (!$foreignTable || !$localTable){
            throw new Exception(
                sprintf(
                    'Missing table parameters. Can not import one-to-many associations for type "%s".',
                    $reference
                ),
                1725689184
            );
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($foreignTable);

        try {
            $connection->beginTransaction();

            $foreignEntityUids = [];
            foreach ($associationData as $item) {

                $mappedData = $this->mapData($item, $foreignMappingConfig);

                $existingEntity = $connection->select(
                    ['uid', 'checksum'],
                    $foreignTable,
                    [
                        $uniqueKeyField => $mappedData[$uniqueKeyField],
                        $uniqueLanguageField => $this->getSwLanguageId()
                    ]
                )->fetchAssociative();

                if ($existingEntity) {

                    $foreignEntityUid = (int)$existingEntity['uid'];
                    if ($existingEntity['checksum'] !== $mappedData['checksum']) {
                        $connection->update(
                            $foreignTable,
                            $mappedData,
                            [
                                'uid' => $foreignEntityUid
                            ]
                        );
                    }

                } else {
                    $connection->insert(
                        $foreignTable,
                        $mappedData
                    );
                    $foreignEntityUid  = (int)$connection->lastInsertId();
                }

                $foreignEntityUids[] = $foreignEntityUid;
            }

            if ($localField) {

                //  Update the relation field in the local table with a comma-separated list
                $this->updateOneToManyRelationField($localTable, $entityUid, $localField, $foreignEntityUids);

            } elseif ($foreignField) {

                // Update the parent reference field in the foreign table
                foreach ($foreignEntityUids as $foreignEntityUid) {
                    $this->updateParentRelationField($foreignTable, $foreignEntityUid, $foreignField, $entityUid);
                }
            }

            $connection->commit();

        } catch (\Throwable $e) {
            $connection->rollBack();
            $this->logger->error(
                sprintf(
                    'Error while processing association to %s from table %s for %s: %s',
                    $reference,
                    $foreignTable,
                    $entityUid,
                    $e->getMessage()
                )
            );
            throw $e;
        }
    }



    /**
     * Update the relation field of a given entity for a one-to-many relationship.
     *
     * @param string $table
     * @param int $entityUid
     * @param string $relationField
     * @param array $relations
     * @return void
     * @internal
     */
    protected function updateOneToManyRelationField(string $table, int $entityUid, string $relationField, array $relations): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($table);

        $connection->update(
            $table,
            [$relationField => implode(',', $relations)],
            ['uid' => $entityUid]
        );
    }


    /**
     * Update the parent reference field in the foreign table for a many-to-one relationship.
     *
     * @param string $table
     * @param int $entityUid
     * @param string $relationField
     * @param int $parentUid
     * @return void
     * @internal
     */
    protected function updateParentRelationField(string $table, int $entityUid, string $relationField, int $parentUid): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($table);

        $connection->update(
            $table,
            [$relationField => $parentUid],
            ['uid' => $entityUid]
        );
    }


    /**
     * Maps data from Shopware to TYPO3 database fields based on the YAML mapping.
     *
     * @param array $data
     * @param array $mapping
     * @return array
     * @throws \Exception
     */
    public function mapData(array $data, array $mapping): array
    {
        $mappedData = [];
        foreach ($mapping['fields'] as $field) {

            // Ensure both apiField and tableField exist
            if ($field['apiField'] && $field['tableField']) {
                $value = $this->getValueFromPath($data, $field['apiField']);
                $mappedData[$field['tableField']] = $this->convertType($value, $field['type']);
            }
        }

        // add checksum, language ID and pid
        $mappedData['sw_language_id'] = $this->getSwLanguageId();
        $mappedData['pid'] = $this->getPId();
        $mappedData['checksum'] = md5(json_encode($mappedData));

        return $mappedData;
    }


    /**
     * Retrieves a value from a nested array using a dot-separated path.
     *
     * @param array $data
     * @param string $path
     * @return mixed
     */
    public function getValueFromPath(array $data, string $path)
    {
        $keys = explode('.', $path);
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $data = $data[$key];
            } else {
                return null;
            }
        }
        return $data;
    }


    /**
     * Converts a value to the specified type.
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     * @throws \Exception
     */
    public function convertType($value, string $type)
    {
        switch ($type) {
            case 'bool':
            case 'boolean':
                return $value ? 1 : 0;
            case 'booleanInverted':
                return !$value ? 1 : 0;
            case 'dateTime':
                return $value
                    ? (new \DateTime($value))->format('Y-m-d H:i:s')
                    : '0000-00-00 00:00:00';
            case 'float':
                return (float)$value;
            case 'int':
            case 'integer':
                return (int)$value;
            case 'json':
                return json_encode($value);
            case 'string':
                return (string)$value;
            case 'timestamp':
                return $value
                    ? (new \DateTime($value))->getTimestamp()
                    : 0;
            case 'none':
            default:
                return $value;
        }
    }
}
