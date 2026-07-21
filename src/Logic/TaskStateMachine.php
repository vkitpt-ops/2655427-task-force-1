<?php

declare(strict_types=1);

namespace TaskForce\Logic;

use TaskForce\Logic\Enums\TaskStatus;
use TaskForce\Logic\Enums\TaskAction;
use TaskForce\Logic\Exceptions\TaskException;

/**
 * Управляет состояниями задания.
 *
 * Определяет:
 * - действия, доступные для текущего статуса;
 * - действия, разрешённые конкретному пользователю;
 * - переходы задания между статусами.
 */
class TaskStateMachine
{
    /**
     * Возвращает список действий, доступных для указанного статуса.
     *
     * @param TaskStatus $status Текущий статус задания.
     *
     * @return array Список доступных действий.
     */
    public function getAvailableActions(TaskStatus $status): array
    {
        return array_filter(TaskAction::cases(), fn(TaskAction $action) => in_array($status, $action->availableInStatuses(), true));
    }

    /**
     * Возвращает список действий, которые доступны текущему пользователю.
     *
     * Выполняет проверку прав пользователя для каждого действия,
     * доступного в текущем статусе задания.
     *
     * @param TaskStatus $status Текущий статус задания.
     * @param int $customerId ID заказчика.
     * @param int|null $currentUserId ID текущего пользователя.
     * @param int|null $executorId ID исполнителя задания.
     *
     * @return array Список разрешённых действий.
     */
    public function getAllowedActions(TaskStatus $status, int $customerId, ?int $currentUserId, ?int $executorId): array
    {
        $cases = $this->getAvailableActions($status);
        return array_filter($cases, fn(TaskAction $action) => $action->checkRights($customerId, $executorId, $currentUserId, $status));
    }

    /**
     * Выполняет переход задания в новый статус.
     *
     * Проверяет, что указанное действие допустимо для текущего статуса,
     * и возвращает новый статус задания.
     *
     * @param TaskStatus $current Текущий статус задания.
     * @param TaskAction $action Выполняемое действие.
     *
     * @return TaskStatus Новый статус задания.
     *
     * @throws TaskException Если действие недоступно для текущего статуса.
     */
    public function transition(TaskStatus $current, TaskAction $action): TaskStatus
    {
        if (!in_array($current, $action->availableInStatuses(), true)) {
            throw new TaskException("Действие {$action->label()} недоступно для статуса {$current->label()}.");
        }

        return $action->resultingStatus();
    }
}
