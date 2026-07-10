<?php

declare(strict_types=1);

namespace TaskForce\Logic\Actions;

class CancelAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Отменить';
    }

    public function getInnerName(): string
    {
        return 'cancel';
    }

    public function checkRights(int $customerId, ?int $executorId, int $currentUserId): bool
    {
        return $customerId === $currentUserId;
    }
}
