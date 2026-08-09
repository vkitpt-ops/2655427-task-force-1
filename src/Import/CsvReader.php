<?php

declare(strict_types=1);

namespace TaskForce\Import;

use Generator;
use SplFileObject;

/**
 * Читает CSV-файл и возвращает его содержимое построчно.
 */
class CsvReader
{
    /**
     * Считывает данные из CSV-файла.
     *
     * Первая строка используется в качестве заголовков столбцов.
     * Каждая последующая строка возвращается в виде ассоциативного массива.
     *
     * @param string $fileName Путь к CSV-файлу.
     *
     * @return Generator<int, array<string, string|null>>
     */
    public function read(string $fileName): Generator
    {
        $file = new SplFileObject($fileName);

        $file->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::SKIP_EMPTY
        );

        $header = null;

        foreach ($file as $row) {
            if ($row === [null]) {
                continue;
            }

            if ($header === null) {
                $header = $row;
                continue;
            }

            yield array_combine($header, $row);
        }
    }
}