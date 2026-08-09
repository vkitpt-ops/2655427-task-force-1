<?php

declare(strict_types=1);

namespace TaskForce\Import;

use RuntimeException;

/**
 * Выполняет импорт данных из CSV-файла в SQL-файл.
 */
class ImportRunner
{
    private CsvReader $reader;
    private SqlConverter $converter;

    public function __construct()
    {
        $this->reader = new CsvReader();
        $this->converter = new SqlConverter();
    }

    /**
     * Конвертирует CSV-файл в SQL-файл.
     *
     * @param string $csvFile Путь к CSV-файлу.
     * @param string $table Имя таблицы базы данных.
     * @param string $outputFile Путь к SQL-файлу.
     */
    public function run(
        string $csvFile,
        string $table,
        string $outputFile
    ): void {
        $rows = $this->reader->read($csvFile);

        $file = fopen($outputFile, 'w');

        if ($file === false) {
            throw new RuntimeException(
                "Не удалось открыть файл {$outputFile}"
            );
        }

        $firstRow = true;

        foreach ($rows as $row) {
            if ($firstRow) {
                $columns = implode(', ', array_keys($row));

                fwrite(
                    $file,
                    sprintf(
                        "INSERT INTO %s (%s) VALUES\n",
                        $table,
                        $columns
                    )
                );

                $firstRow = false;
            } else {
                fwrite($file, ",\n");
            }

            fwrite(
                $file,
                $this->converter->convertRow($row)
            );
        }

        if (!$firstRow) {
            fwrite($file, ";\n");
        }

        fclose($file);
    }
}
