<?php

declare(strict_types=1);

namespace TaskForce\Logic\Enums;

use InvalidArgumentException;

/**
 * Перечисление всех возможных статусов задания.
 */
enum TaskStatus: string
{
    case New = 'new';
    case Cancel = 'cancel';
    case Work = 'work';
    case Done = 'done';
    case Failed = 'failed';

    /**
     * Возвращает отображаемое название статуса.
     *
     * @return string Название статуса на русском языке.
     */
    public function label(): string
    {
        return match($this) {
            self::New => 'Новое',
            self::Cancel => 'Отменено',
            self::Work => 'В работе',
            self::Done => 'Выполнено',
            self::Failed => 'Провалено',
            default => throw new InvalidArgumentException("Статус {$this->name} не имеет заданного лейбла."),
        };
    }
}
