<?php

declare(strict_types=1);

namespace TaskForce\Logic\Actions;

class RefuseAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Отказаться';
    }

    public function getInnerName(): string
    {
        return 'refuse';
    }

    public function checkRights(int $customerId, ?int $executorId, int $currentUserId): bool
    {
        return $executorId === $currentUserId;
    }
}
