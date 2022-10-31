<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Language\TranslationService as BaseTranslationService;

/**
 * Class TranslationService
 *
 * @package CleverReachCore\Business\Service
 */
class TranslationService extends BaseTranslationService
{
    const CLASS_NAME = __CLASS__;

    /**
     * Returns current system language.
     *
     * @return string
     */
    public function getSystemLanguage(): string
    {
        return '';
    }
}
