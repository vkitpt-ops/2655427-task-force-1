<?php

declare(strict_types=1);

namespace TaskForce\Logic\Actions;

abstract class AbstractAction
{
    abstract public function getName(): string;

    abstract public function getInnerName(): string;

    abstract public function checkRights(
        int $customerId,
        ?int $executorId,
        int $currentUserId
    ): bool;
}
