<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class ResourceImporter
{
    /**
     * Inserts or updates the given resource.
     *
     * @param       $table
     * @param       $rows
     * @param array $exclude The attributes to exclude in case of update.
     */
    public static function insertOrUpdate($table, $rows, array $exclude = [])
    {
        // We assume all rows have the same keys so we arbitrarily pick one of them.
        $columns = array_keys($rows[0]);

        $columnsString = implode('`,`', $columns);
        $values = self::buildSQLValuesFrom($rows);
        $updates = self::buildSQLUpdatesFrom($columns, $exclude);
        $params = array_flatten($rows);

        $query = "insert into {$table} (`{$columnsString}`) values {$values} on duplicate key update {$updates}";

        DB::statement($query, $params);

        return $query;
    }

    /**
     * Build proper SQL string for the values.
     *
     * @param array $rows
     * @return string
     */
    protected static function buildSQLValuesFrom(array $rows)
    {
        $values = collect($rows)->reduce(function ($valuesString, $row) {
            return $valuesString .= '(' . rtrim(str_repeat("?,", count($row)), ',') . '),';
        }, '');

        return rtrim($values, ',');
    }

    /**
     * Build proper SQL string for the on duplicate update scenario.
     *
     * @param       $columns
     * @param array $exclude
     * @return string
     */
    protected static function buildSQLUpdatesFrom($columns, array $exclude)
    {
        $updateString = collect($columns)->reject(function ($column) use ($exclude) {
            return in_array($column, $exclude);
        })->reduce(function ($updates, $column) {
            return $updates .= "`{$column}`=VALUES(`{$column}`),";
        }, '');

        return trim($updateString, ',');
    }
}