<?php

declare(strict_types=1);

namespace TaskForce\Logic\Enums;

use TaskForce\Logic\Enums\TaskStatus;

/**
 * Перечисление всех возможных действий над заданием.
 */
enum TaskAction: string
{
    case Respond = 'respond';
    case Cancel = 'cancel';
    case Start = 'start';
    case Finish = 'finish';
    case Refuse = 'refuse';

    /**
     * Возвращает список статусов, в которых доступно данное действие.
     *
     * @return array Список допустимых статусов.
     */
    public function availableInStatuses(): array
    {
        return match($this) {
            self::Respond => [TaskStatus::New],
            self::Cancel => [TaskStatus::New],
            self::Start => [TaskStatus::New],
            self::Finish => [TaskStatus::Work],
            self::Refuse => [TaskStatus::Work],
        };
    }

    /**
     * Возвращает статус, в который переходит задание после выполнения действия.
     *
     * @return TaskStatus Новый статус задания.
     *
     * @throws LogicException Если действие не приводит к смене статуса.
     */
    public function resultingStatus(): TaskStatus
    {
        return match ($this) {
            self::Cancel => TaskStatus::Cancel,
            self::Start => TaskStatus::Work,
            self::Finish => TaskStatus::Done,
            self::Refuse => TaskStatus::Failed,
            self::Respond => throw new \LogicException("Respond action does not change status directly"),
        };
    }

    /**
     * Проверяет, имеет ли пользователь право выполнить действие.
     *
     * @param int $customerId ID заказчика задания.
     * @param int|null $executorId ID исполнителя задания.
     * @param int $currentUserId ID текущего пользователя.
     *
     * @return bool Возвращает true, если действие доступно пользователю.
     */
    public function checkRights(int $customerId, ?int $executorId, int $currentUserId, TaskStatus $currentStatus): bool
    {
        return match ($this) {
            self::Respond => $customerId !== $currentUserId && $executorId === null,
            self::Cancel => $customerId === $currentUserId,
            self::Start => $customerId === $currentUserId && $executorId !== null,
            self::Finish => $customerId === $currentUserId && $executorId !== null,
            self::Refuse => $executorId === $currentUserId,
        };
    }

    /**
     * Возвращает отображаемое название действия.
     *
     * @return string Название действия на русском языке.
     */
    public function label(): string
    {
        return match ($this) {
            self::Respond => 'Откликнуться',
            self::Cancel => 'Отменить',
            self::Start => 'Принять',
            self::Finish => 'Завершить',
            self::Refuse => 'Отказаться',
        };
    }
}
