<?php

namespace IssuesBundle\Tests\Unit\Entity;

trait EntityTestTrait
{
    private function assertSettingAndGettingValuesKeepsConsistency($class)
    {
        $classReflection = new \ReflectionClass($class);

        $entity = new $class();

        foreach ($classReflection->getMethods() as $method) {
            if (substr($method->getName(), 0, 3) == 'set') {
                $propertyName = substr($method->getName(), 3);

                $params = $method->getParameters();

                $value = 'test';

                if ($params[0]->getClass()) {
                    $value = $this->getMock($params[0]->getClass()->getName());
                }

                $entity->{'set'.$propertyName}($value);

                $this->assertEquals($entity->{'get'.$propertyName}(), $value);
            }
        }
    }
}