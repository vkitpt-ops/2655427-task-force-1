<?php

declare(strict_types=1);

namespace TaskForce\Import;

use SplFileObject;

/**
 * Чтение CSV-файла и преобразование его содержимое в массив ассоциативных массивов.
 */
class CsvReader
{
    /**
     * Считывание данных из CSV-файла.
     *
     * Первая строка файла используется в качестве заголовков столбцов.
     * Каждая последующая строка преобразуется в ассоциативный массив,
     * где ключами являются названия столбцов.
     *
     * @param string $file_name Путь к CSV-файлу.
     *
     * @return array Массив записей из CSV.
     */
    public function read(string $file_name): array
    {
        $file = new SplFileObject($file_name);

        $file->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::SKIP_EMPTY
        );

        $rows = [];
        $header = null;

        foreach ($file as $row) {
            if ($row === [null]) {
                continue;
            }

            if ($header === null) {
                $header = $row;
                continue;
            }

            $rows[] = array_combine($header, $row);
        }

        return $rows;
    }
}
