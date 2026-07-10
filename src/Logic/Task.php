<?php

declare(strict_types=1);

namespace TaskForce\Logic;

use TaskForce\Logic\Actions\RespondAction;
use TaskForce\Logic\Actions\CancelAction;
use TaskForce\Logic\Actions\StartAction;
use TaskForce\Logic\Actions\FinishAction;
use TaskForce\Logic\Actions\RefuseAction;


/**
 * Класс Task описывает бизнес-логику задания.
 *
 * Определяет:
 * - возможные статусы задания;
 * - переходы между статусами;
 * - доступные действия в зависимости от статуса;
 * - действия, доступные текущему пользователю.
 */
class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_WORK = 'work';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public const ACTION_RESPOND = 'respond';
    public const ACTION_CANCEL = 'cancel';
    public const ACTION_START = 'start';
    public const ACTION_FINISH = 'finish';
    public const ACTION_REFUSE = 'refuse';

    private int $customerId;
    private ?int $executorId;

    /**
     * Создаёт объект задания.
     *
     * @param int $customerId ID заказчика задания.
     * @param int|null $executorId ID исполнителя задания или null, если исполнитель ещё не назначен.
     */
    public function __construct(int $customerId, ?int $executorId = null)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
    }

    /**
     * Возвращает список всех статусов задания.
     *
     * @return array Массив вида [код статуса => название].
     */
    public function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCEL => 'Отменено',
            self::STATUS_WORK => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено',
        ];
    }

    /**
     * Определяет следующий статус задания для указанного действия.
     *
     * @param string $action Внутреннее имя действия.
     *
     * @return string|null Следующий статус или null, если действие не приводит к смене статуса.
     */
    public function getNextStatus(string $action): ?string
    {
        $map = [
            self::ACTION_CANCEL => self::STATUS_CANCEL,
            self::ACTION_START => self::STATUS_WORK,
            self::ACTION_FINISH => self::STATUS_DONE,
            self::ACTION_REFUSE => self::STATUS_FAILED,
        ];

        return $map[$action] ?? null;
    }

    /**
     * Возвращает список действий, доступных для указанного статуса.
     *
     * @return array Список объектов действий.
     */
    private function getActionsByStatus(string $status): array
    {
        switch ($status) {

            case self::STATUS_NEW:
                return [
                    new RespondAction(),
                    new CancelAction(),
                    new StartAction()
                ];

            case self::STATUS_WORK:
                return [
                    new FinishAction(),
                    new RefuseAction()
                ];

            default:
                return [];
        }
    }

    /**
     * Возвращает список действий, которые доступны текущему пользователю.
     *
     * Сначала определяются действия, доступные для статуса задания,
     * затем они фильтруются с помощью проверки прав.
     *
     * @param string $status Текущий статус задания.
     * @param int $currentUserId ID текущего пользователя.
     *
     * @return array Список объектов действий.
     */
    public function getAvailableActions(string $status, int $currentUserId): array
    {
        $actions = $this->getActionsByStatus($status);
        $availableActions = [];

        foreach ($actions as $action) {
            if ($action->checkRights(
                $this->customerId,
                $this->executorId,
                $currentUserId
            )) {
                $availableActions[] = $action;
            }
        }

        return $availableActions;
    }
}
