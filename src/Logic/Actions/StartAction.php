<?php

declare(strict_types=1);

namespace TaskForce\Logic\Actions;

class StartAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Принять';
    }

    public function getInnerName(): string
    {
        return 'start';
    }

    public function checkRights(int $customerId, ?int $executorId, int $currentUserId): bool
    {
        return $customerId === $currentUserId;
    }
}
