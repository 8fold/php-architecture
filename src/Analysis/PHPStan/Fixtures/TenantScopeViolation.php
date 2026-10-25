<?php

declare(strict_types=1);

namespace MTM\Analysis\PHPStan\Fixtures;

use MTM\Shared\Attributes\Repository;
use MTM\Shared\Attributes\TenantScopeExempt;
use MTM\Shared\Database\DatabaseConnection;

#[Repository]
final class TenantScopeViolation
{
    public static function missingTableScope(DatabaseConnection $db): void
    {
        $db->fetchOne(
            'SELECT `id` FROM `Activities` WHERE `id` = :id',
            ['id' => 'id']
        );
    }

    public static function missingTenantBinding(DatabaseConnection $db): void
    {
        $db->fetchOne(
            'SELECT `id` FROM `TimeEntries` AS te'
            . ' WHERE te.`tenantId` = :tenantId AND te.`id` = :id',
            ['id' => 'id']
        );
    }

    public static function oneJoinTableIsUnscoped(DatabaseConnection $db): void
    {
        $db->fetchAll(
            <<<'SQL'
                SELECT a.`id` FROM `Activities` AS a
                INNER JOIN `TimeEntries` AS te ON te.`activityId` = a.`id`
                WHERE a.`tenantId` = :tenantId
                SQL,
            ['tenantId' => 'tenant']
        );
    }

    public static function unaliasedTableCannotBorrowJoinScope(DatabaseConnection $db): void
    {
        $db->fetchAll(
            <<<'SQL'
                SELECT Activities.`id` FROM `Activities`
                INNER JOIN `TimeEntries` AS te ON te.`activityId` = Activities.`id`
                WHERE te.`tenantId` = :tenantId
                SQL,
            ['tenantId' => 'tenant']
        );
    }

    public static function bindingsAreSpecificToEachQuery(DatabaseConnection $db): void
    {
        $db->fetchOne(
            'SELECT `id` FROM `Activities` WHERE `tenantId` = :tenantId',
            ['id' => 'id']
        );
        $db->fetchOne(
            'SELECT `id` FROM `TimeEntries` WHERE `tenantId` = :tenantId',
            ['tenantId' => 'tenant']
        );
    }

    public static function insertMustIncludeTenantColumn(DatabaseConnection $db): void
    {
        $db->execute(
            'INSERT INTO `TenantUsers` (`accountId`) VALUES (:accountId)',
            ['accountId' => 'account']
        );
    }

    public static function insertMustBindTenantIdForTenantColumn(DatabaseConnection $db): void
    {
        $db->execute(
            'INSERT INTO `TenantUsers` (`tenantId`, `accountId`)'
            . ' VALUES (:accountId, :tenantId)',
            ['tenantId' => 'tenant', 'accountId' => 'account']
        );
    }

    #[TenantScopeExempt(
        'This fixture covers an intentional cross-tenant administrative scan.'
    )]
    public static function exemptCrossTenantScan(DatabaseConnection $db): void
    {
        $db->fetchAll(
            'SELECT `id` FROM `Activities`',
            []
        );
    }
}
