<?php

namespace CleverReachCore\Business;

use CleverReachCore\Business\Service\AuthorizationService;
use CleverReachCore\Business\Service\ConfigService;
use CleverReachCore\Business\Service\DefaultMailingService;
use CleverReachCore\Business\Service\FormService;
use CleverReachCore\Business\Service\GroupService;
use CleverReachCore\Business\Service\ReceiverEventsService;
use CleverReachCore\Business\Service\ReceiverFieldService;
use CleverReachCore\Business\Service\ReceiverSyncService;
use CleverReachCore\Business\Service\RouterService;
use CleverReachCore\Business\Service\SegmentService;
use CleverReachCore\Business\Service\SyncConfigService;
use CleverReachCore\Business\Service\SyncSettingsService;
use CleverReachCore\Business\Service\TranslationService;
use CleverReachCore\Core\BusinessLogic\Authorization\Contracts\AuthorizationService as BaseAuthorizationService;
use CleverReachCore\Core\BusinessLogic\BootstrapComponent;
use CleverReachCore\Core\BusinessLogic\Field\Contracts\FieldService;
use CleverReachCore\Core\BusinessLogic\Form\Contracts\FormService as BaseFormService;
use CleverReachCore\Core\BusinessLogic\Form\Entities\Form;
use CleverReachCore\Core\BusinessLogic\Group\Contracts\GroupService as BaseGroupService;
use CleverReachCore\Core\BusinessLogic\Language\Contracts\TranslationService as BaseTranslationService;
use CleverReachCore\Core\BusinessLogic\Mailing\Contracts\DefaultMailingService as BaseMailingService;
use CleverReachCore\Core\BusinessLogic\Receiver\Contracts\SyncConfigService as BaseSyncConfigService;
use CleverReachCore\Core\BusinessLogic\Receiver\DTO\Config\SyncService;
use CleverReachCore\Core\BusinessLogic\Receiver\ReceiverEventsService as BaseReceiverEventsService;
use CleverReachCore\Core\BusinessLogic\Segment\Contracts\SegmentService as BaseSegmentService;
use CleverReachCore\Core\BusinessLogic\SyncSettings\Contracts\SyncSettingsService as BaseSyncSettingsService;
use CleverReachCore\Core\Infrastructure\Configuration\ConfigEntity;
use CleverReachCore\Core\Infrastructure\Configuration\Configuration;
use CleverReachCore\Core\Infrastructure\ORM\Exceptions\RepositoryClassException;
use CleverReachCore\Core\Infrastructure\ORM\RepositoryRegistry;
use CleverReachCore\Core\Infrastructure\Serializer\Concrete\JsonSerializer;
use CleverReachCore\Core\Infrastructure\Serializer\Serializer;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Core\Infrastructure\TaskExecution\Process;
use CleverReachCore\Core\Infrastructure\TaskExecution\QueueItem;
use CleverReachCore\DataAccess\BaseRepository;
use CleverReachCore\DataAccess\QueueItemRepository;

/**
 * Class Bootstrap
 *
 * @package CleverReachCore\Business
 */
class Bootstrap extends BootstrapComponent
{
    /**
     * Initializes services and utilities.
     *
     * @return void
     */
    public static function initServices(): void
    {
        parent::initServices();

        ServiceRegister::registerService(
            Configuration::class,
            static function() {
                return ConfigService::getInstance();
            }
        );
        ServiceRegister::registerService(
            BaseAuthorizationService::class,
            function() {
                return new AuthorizationService();
            }
        );
        ServiceRegister::registerService(
            Serializer::class,
            static function() {
                return new JsonSerializer();
            }
        );
        ServiceRegister::registerService(
            BaseGroupService::class,
            static function() {
                return new GroupService();
            }
        );
        ServiceRegister::registerService(
            BaseFormService::class,
            static function() {
                return new FormService();
            }
        );
        ServiceRegister::registerService(
            BaseMailingService::class,
            static function() {
                return new DefaultMailingService();
            }
        );
        ServiceRegister::registerService(
            FieldService::class,
            static function() {
                return new ReceiverFieldService();
            }
        );
        ServiceRegister::registerService(
            BaseTranslationService::class,
            static function() {
                return TranslationService::getInstance();
            }
        );
        ServiceRegister::registerService(
            BaseSegmentService::class,
            static function() {
                return new SegmentService();
            }
        );
        ServiceRegister::registerService(
            BaseSyncConfigService::class,
            static function() {
                return new SyncConfigService();
            }
        );
        ServiceRegister::registerService(
            SyncService::class,
            static function() {
                return new ReceiverSyncService();
            }
        );
        ServiceRegister::registerService(
            BaseSyncSettingsService::class,
            static function() {
                return new SyncSettingsService();
            }
        );
        ServiceRegister::registerService(
            ReceiverSyncService::class,
            static function() {
                return new ReceiverSyncService();
            }
        );
        ServiceRegister::registerService(
            BaseReceiverEventsService::class,
            static function() {
                return new ReceiverEventsService();
            }
        );
        ServiceRegister::registerService(
            RouterService::class,
            static function() {
                return new RouterService();
            }
        );
    }

    /**
     * Initializes repositories.
     *
     * @return void
     * @throws RepositoryClassException
     */
    public static function initRepositories(): void
    {
        parent::initRepositories();

        RepositoryRegistry::registerRepository(ConfigEntity::class, BaseRepository::getClassName());
        RepositoryRegistry::registerRepository(QueueItem::class, QueueItemRepository::getClassName());
        RepositoryRegistry::registerRepository(Form::class, BaseRepository::getClassName());
        RepositoryRegistry::registerRepository(Process::CLASS_NAME, BaseRepository::getClassName());
    }
}
