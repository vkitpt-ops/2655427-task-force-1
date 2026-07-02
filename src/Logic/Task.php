<?php

declare(strict_types=1);

namespace TaskForce\Logic;

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
    private ?int $workerId;

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

    public function getActionsMap(): array
    {
        return [
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_START => 'Принять',
            self::ACTION_FINISH => 'Завершить',
            self::ACTION_REFUSE => 'Отказаться',
        ];
    }

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

    public function getAvailableActions(string $status): array
    {
        switch ($status) {

            case self::STATUS_NEW:
                return [
                    self::ACTION_RESPOND,
                    self::ACTION_CANCEL,
                    self::ACTION_START
                ];

            case self::STATUS_WORK:
                return [
                    self::ACTION_FINISH,
                    self::ACTION_REFUSE
                ];

            default:
                return [];
        }
    }

    public function __construct(int $customerId, ?int $workerId = null)
    {
        $this->customerId = $customerId;
        $this->workerId = $workerId;
    }
}
