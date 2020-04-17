<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Orba\Ceneopl\Helper\Serialize\Test\Unit\Serializer;

class JsonConverterTest extends \PHPUnit\Framework\TestCase
{
    public function testConvert()
    {
        $data = [
            'key' => 'value'
        ];

        $this->assertEquals(json_encode($data), \Orba\Ceneopl\Helper\Serialize\JsonConverter::convert($data));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to serialize value.
     */
    public function testConvertWithException()
    {
        //verify that exception will be thrown with invalid UTF8 sequence
        \Orba\Ceneopl\Helper\Serialize\JsonConverter::convert("\xB1\x31");
    }
}
