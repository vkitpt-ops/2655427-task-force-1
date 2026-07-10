<?php

declare(strict_types=1);

namespace TaskForce\Logic\Actions;

class RespondAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Откликнуться';
    }

    public function getInnerName(): string
    {
        return 'respond';
    }

    public function checkRights(int $customerId, ?int $executorId, int $currentUserId): bool
    {
        return $customerId !== $currentUserId;
    }
}
