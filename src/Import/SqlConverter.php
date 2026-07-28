<?php

declare(strict_types=1);

namespace TaskForce\Import;

/**
 * Фщрматирование SQL-запросов из данных CSV.
 */
class SqlConverter
{
    /**
     * Преобразование массива записей в SQL-запрос INSERT.
     *
     * @param string $table Имя таблицы базы данных.
     * @param array $rows Массив записей, где каждый элемент представляет строку данных.
     *
     * @return string Готовый SQL-запрос или пустая строка, если массив записей пуст.
     */
    public function convert(string $table, array $rows): string
    {
        if (!$rows) {
            return '';
        }

        $columns = array_keys($rows[0]);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES\n",
            $table,
            implode(', ', $columns)
        );

        $values = [];

        foreach ($rows as $row) {

            $items = [];

            foreach ($row as $value) {

                if ($value === '') {
                    $items[] = 'NULL';
                } elseif (is_numeric($value)) {
                    $items[] = $value;
                } else {
                    $items[] = "'" . addslashes($value) . "'";
                }
            }

            $values[] = '(' . implode(', ', $items) . ')';
        }

        $sql .= implode(",\n", $values);
        $sql .= ";\n";

        return $sql;
    }
}
