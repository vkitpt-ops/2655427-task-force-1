<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use TaskForce\Logic\Task;

$task = new Task(5, 10);

assert(
    $task->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCEL,
    'Cancel'
);

assert(
    $task->getNextStatus(Task::ACTION_START) === Task::STATUS_WORK,
    'Start'
);

assert(
    $task->getNextStatus(Task::ACTION_FINISH) === Task::STATUS_DONE,
    'Finish'
);

assert(
    $task->getNextStatus(Task::ACTION_REFUSE) === Task::STATUS_FAILED,
    'Refuse'
);

echo "Все тесты прошли.";
