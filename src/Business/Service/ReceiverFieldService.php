<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Field\Contracts\FieldType;
use CleverReachCore\Core\BusinessLogic\Field\DTO\Field;
use CleverReachCore\Core\BusinessLogic\Field\FieldService;
use CleverReachCore\Core\BusinessLogic\Language\Contracts\TranslationService as BaseTranslationService;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ReceiverFieldService
 *
 * @package CleverReachCore\Business\Service
 */
class ReceiverFieldService extends FieldService
{
    protected static $supportedFieldsMap = array(
        'salutation' => FieldType::TEXT,
        'firstname' => FieldType::TEXT,
        'lastname' => FieldType::TEXT,
        'street' => FieldType::TEXT,
        'street_number' => FieldType::TEXT,
        'zip' => FieldType::TEXT,
        'city' => FieldType::TEXT,
        'company' => FieldType::TEXT,
        'state' => FieldType::TEXT,
        'country' => FieldType::TEXT,
        'birthday' => FieldType::TEXT,
        'phone' => FieldType::TEXT,
        'language' => FieldType::TEXT,
        'shop' => FieldType::TEXT,
        'customer_number' => FieldType::TEXT,
        'order_count' => FieldType::NUMBER,
        'total_spent' => FieldType::NUMBER
    );

    /**
     * Retrieve list of fields that an integration supports.
     *
     * @return \CleverReachCore\Core\BusinessLogic\Field\DTO\Field[]
     * @throws \CleverReachCore\Core\Infrastructure\ORM\Exceptions\QueryFilterInvalidParamException
     */
    public function getEnabledFields()
    {
        $supportedFieldsMap = array();
        foreach ($this->getSupportedFields() as $field) {
            $supportedFieldsMap[$field->getName()] = $field->getType();
        }

        return $this->createFieldsList($supportedFieldsMap);
    }
}
