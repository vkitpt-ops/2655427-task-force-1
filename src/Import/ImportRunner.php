<?php

declare(strict_types=1);

namespace TaskForce\Import;

/**
 * Выполнение импорта данных из CSV-файла в SQL-файл.
 */
class ImportRunner
{
    private CsvReader $reader;
    private SqlConverter $converter;

    /**
     * Создание экземпляров класса для чтения CSV и генерации SQL.
     */
    public function __construct()
    {
        $this->reader = new CsvReader();
        $this->converter = new SqlConverter();
    }

    /**
     * Конвертация данных из CSV-файла в SQL и сохранение результата в файл.
     *
     * @param string $csvFile Путь к CSV-файлу.
     * @param string $table Имя таблицы базы данных.
     * @param string $output_file Путь к создаваемому SQL-файлу.
     */
    public function run(
        string $csvFile,
        string $table,
        string $output_file
    ): void {

        $rows = $this->reader->read($csvFile);

        $sql = $this->converter->convert($table, $rows);

        file_put_contents($output_file, $sql);
    }
}
