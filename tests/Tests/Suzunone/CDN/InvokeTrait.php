<?php

namespace Tests\Suzunone\CDN;

/**
 * Class InvokeTrait
 *
 * @category    Tests
 * @package     \Suzunone\CDN
 * @codeCoverageIgnore
 */
trait InvokeTrait
{
    /**
     * @param object $instance
     * @param string $method_name
     * @param array $options
     * @return mixed
     * @throws \ReflectionException
     */
    public function invokeExecuteMethod($instance, string $method_name, array $options)
    {
        $reflection = new \ReflectionClass($instance);
        $method     = $reflection->getMethod($method_name);
        $method->setAccessible(true);

        return $method->invokeArgs($instance, $options);
    }

    /**
     * @param object $instance
     * @param string $property_name
     * @return mixed
     * @throws \ReflectionException
     */
    public function invokeGetProperty($instance, string $property_name)
    {
        $reflection = new \ReflectionClass($instance);
        $property   = $reflection->getProperty($property_name);
        $property->setAccessible(true);

        return $property->getValue($instance);
    }

    /**
     * @param object $instance
     * @param string $property_name
     * @param mixed $data
     * @throws \ReflectionException
     */
    public function invokeSetProperty($instance, string $property_name, $data)
    {
        $reflection = new \ReflectionClass($instance);
        $property   = $reflection->getProperty($property_name);
        $property->setAccessible(true);

        $property->setValue($instance, $data);
    }
}
