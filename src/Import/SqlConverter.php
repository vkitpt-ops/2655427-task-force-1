<?php

declare(strict_types=1);

namespace TaskForce\Import;

/**
 * Форматирует данные CSV в SQL-запрос INSERT.
 */
class SqlConverter
{
    /**
     * Форматирует одну запись в SQL-значения.
     *
     * @param array $row Запись из CSV.
     *
     * @return string Строка SQL со значениями.
     */
    public function convertRow(array $row): string
    {
        $items = array_map(
            static function ($value): string {
                if ($value === '') {
                    return 'NULL';
                }

                if (is_numeric($value)) {
                    return (string) $value;
                }

                return "'" . addslashes($value) . "'";
            },
            $row
        );

        return '(' . implode(', ', $items) . ')';
    }
}
