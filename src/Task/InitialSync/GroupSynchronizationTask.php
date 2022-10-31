<?php

namespace CleverReachCore\Task\InitialSync;

use CleverReachCore\Core\BusinessLogic\Form\Tasks\CacheFormsTask;
use CleverReachCore\Core\BusinessLogic\Form\Tasks\CreateDefaultFormTask;
use CleverReachCore\Core\BusinessLogic\Group\Tasks\CreateGroupTask;
use CleverReachCore\Core\BusinessLogic\InitialSynchronization\Tasks\Composite\Components\GroupSynchronization;
use CleverReachCore\Core\BusinessLogic\Mailing\Tasks\CreateDefaultMailing;
use CleverReachCore\Core\BusinessLogic\Receiver\Tasks\RegisterReceiverEventsTask;

/**
 * class GroupSynchronizationTask
 *
 * @package CleverReachCore\Task\InitialSync
 */
class GroupSynchronizationTask extends GroupSynchronization
{
    const CLASS_NAME = __CLASS__;

    /**
     * GroupSynchronization constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves sub tasks.
     *
     * @return array
     */
    protected function getSubTasks(): array
    {
        return [
            CreateGroupTask::CLASS_NAME => 10,
            CreateDefaultFormTask::CLASS_NAME => 15,
            CacheFormsTask::CLASS_NAME => 65,
            CreateDefaultMailing::CLASS_NAME => 5,
            RegisterReceiverEventsTask::CLASS_NAME => 5,
        ];
    }
}
