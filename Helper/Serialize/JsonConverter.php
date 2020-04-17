<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Orba\Ceneopl\Helper\Serialize;

/**
 * This class was introduced only for usage in the \Magento\Framework\DataObject::toJson method.
 * It should not be used in other cases and instead \Orba\Ceneopl\Helper\Serialize\Serializer\Json::serialize
 * should be used.
 */
class JsonConverter
{
    /**
     * This method should only be used by \Magento\Framework\DataObject::toJson
     * All other cases should use \Orba\Ceneopl\Helper\Serialize\Serializer\Json::serialize directly
     *
     * @param string|int|float|bool|array|null $data
     * @return bool|string
     * @throws \InvalidArgumentException
     */
    public static function convert($data)
    {
        $serializer = new \Orba\Ceneopl\Helper\Serialize\Serializer\Json();
        return $serializer->serialize($data);
    }
}
