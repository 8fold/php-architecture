<?php

declare(strict_types=1);

namespace MTM\Analysis\PHPStan\Fixtures;

use MTM\Shared\Attributes\Repository;
use MTM\Shared\Database\DatabaseConnection;

#[Repository]
final class TenantScopeFlow
{
    public static function laterSqlMustNotMaskEarlierQuery(DatabaseConnection $db): void
    {
        $sql = 'SELECT id FROM Activities';
        $db->fetchAll($sql, ['tenantId' => 'tenant']);
        $sql = 'SELECT id FROM Activities WHERE tenantId = :tenantId';
        $db->fetchAll($sql, ['tenantId' => 'tenant']);
    }

    public static function laterBindingsMustNotMaskEarlierQuery(DatabaseConnection $db): void
    {
        $sql = 'SELECT id FROM Activities WHERE tenantId = :tenantId';
        $params = [];
        $db->fetchAll($sql, $params);
        $params = ['tenantId' => 'tenant'];
        $db->fetchAll($sql, $params);
    }

    public static function conditionalBindingIsNotGuaranteed(
        DatabaseConnection $db,
        bool $includeTenant
    ): void {
        $params = $includeTenant ? ['tenantId' => 'tenant'] : [];
        $db->fetchAll('SELECT id FROM Activities WHERE tenantId = :tenantId', $params);
    }

    public static function bindingsCanBeBuiltIncrementally(DatabaseConnection $db): void
    {
        $params = [];
        $params['tenantId'] = 'tenant';
        $db->fetchAll('SELECT id FROM Activities WHERE tenantId = :tenantId', $params);
    }

    public static function namedArgumentsCanBeReordered(DatabaseConnection $db): void
    {
        $db->fetchAll(
            params: ['tenantId' => 'tenant'],
            sql: 'SELECT id FROM Activities WHERE tenantId = :tenantId'
        );
    }

    public static function conditionalSqlMustScopeEveryBranch(
        DatabaseConnection $db,
        bool $scoped
    ): void {
        $sql = $scoped
            ? 'SELECT id FROM Activities WHERE tenantId = :tenantId'
            : 'SELECT id FROM Activities';
        $db->fetchAll($sql, ['tenantId' => 'tenant']);
    }

    public static function nonDatabaseCallsAreNotQueries(object $formatter): void
    {
        $formatter->execute('SELECT id FROM Activities');
    }
}
