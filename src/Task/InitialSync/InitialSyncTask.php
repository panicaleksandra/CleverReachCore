<?php

namespace CleverReachCore\Task\InitialSync;

use CleverReachCore\Core\BusinessLogic\InitialSynchronization\Tasks\Composite\Components\GroupSynchronization;
use CleverReachCore\Core\BusinessLogic\InitialSynchronization\Tasks\Composite\InitialSyncTask as BaseInitialSyncTask;
use CleverReachCore\Core\Infrastructure\TaskExecution\Task;

/**
 * class InitialSyncTask
 *
 * @package CleverReachCore\Task\InitialSync
 */
class InitialSyncTask extends BaseInitialSyncTask
{
    /**
     * Creates a subtask for specified task FQN.
     *
     * @param string $taskKey Fully qualified name of the task.
     *
     * @return Task Created task.
     */
    protected function createSubTask($taskKey): Task
    {
        if ($taskKey === GroupSynchronization::class) {
            return new GroupSynchronizationTask();
        }

        return parent::createSubTask($taskKey);
    }
}
