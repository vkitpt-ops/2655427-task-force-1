<?php

declare(strict_types=1);

namespace TaskForce\Logic\Actions;

class FinishAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Завершить';
    }

    public function getInnerName(): string
    {
        return 'finish';
    }

    public function checkRights(int $customerId, ?int $executorId, int $currentUserId): bool
    {
        return $customerId === $currentUserId;
    }
}
