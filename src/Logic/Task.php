<?php

declare(strict_types=1);

namespace TaskForce\Logic;

use TaskForce\Logic\Enums\TaskAction;
use TaskForce\Logic\Enums\TaskStatus;

/**
 * Представляет задание.
 *
 * Хранит информацию о заказчике, исполнителе и текущем статусе задания.
 * Для определения доступных действий и изменения статуса использует
 * объект TaskStateMachine.
 */
class Task
{
    private int $customerId;
    private ?int $executorId;
    private TaskStatus $status;

    /**
     * Создаёт объект задания.
     *
     * @param int $customerId ID заказчика задания.
     * @param int|null $executorId ID исполнителя задания или null, если исполнитель ещё не назначен.
     * @param TaskStatus $status Текущий статус задания.
     */
    public function __construct(int $customerId, ?int $executorId = null, TaskStatus $status)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
        $this->status = $status;
    }

    /**
     * Возвращает список действий, доступных текущему пользователю.
     *
     * Делегирует проверку доступных действий объекту TaskStateMachine.
     *
     * @param TaskStateMachine $machine Объект конечного автомата задания.
     * @param int $currentUserId ID текущего пользователя.
     *
     * @return array Список доступных действий.
     */
    public function getAvailableActions(TaskStateMachine $machine, int $currentUserId): array
    {
        return $machine->getAllowedActions($this->status, $this->customerId, $currentUserId, $this->executorId);

    }

    /**
     * Выполняет действие над заданием и изменяет его статус.
     *
     * Новый статус определяется объектом TaskStateMachine.
     *
     * @param TaskStateMachine $machine Объект конечного автомата задания.
     * @param TaskAction $action Выполняемое действие.
     */
    public function apply(TaskStateMachine $machine, TaskAction $action): void
    {
        $this->status = $machine->transition($this->status, $action);
    }
}
