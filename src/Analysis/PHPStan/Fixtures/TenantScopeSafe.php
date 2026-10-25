<?php

declare(strict_types=1);

namespace MTM\Analysis\PHPStan\Fixtures;

use MTM\Shared\Attributes\Repository;
use MTM\Shared\Database\DatabaseConnection;

#[Repository]
final class TenantScopeSafe
{
    public static function readsScopedTable(DatabaseConnection $db): void
    {
        $db->fetchOne(
            'SELECT `id` FROM `Activities` WHERE `tenantId` = :tenantId AND `id` = :id',
            ['tenantId' => 'tenant', 'id' => 'id']
        );
    }

    public static function scopesConcatenatedSql(DatabaseConnection $db): void
    {
        $sql = 'SELECT `id` FROM `Activities`'
            . ' WHERE `tenantId` = :tenantId';

        $db->fetchOne($sql, ['tenantId' => 'tenant']);
    }

    public static function insertsTenantScopedRow(DatabaseConnection $db): void
    {
        $db->execute(
            'INSERT INTO `TenantUsers` (`tenantId`, `accountId`)'
            . ' VALUES (:tenantId, :accountId)',
            ['tenantId' => 'tenant', 'accountId' => 'account']
        );
    }

    public static function scopesEachJoinTable(DatabaseConnection $db): void
    {
        $db->fetchAll(
            <<<'SQL'
                SELECT a.`id` FROM `Activities` AS a
                INNER JOIN `TimeEntries` AS te ON te.`activityId` = a.`id`
                WHERE a.`tenantId` = :tenantId AND te.`tenantId` = :tenantId
                SQL,
            ['tenantId' => 'tenant']
        );
    }
}
