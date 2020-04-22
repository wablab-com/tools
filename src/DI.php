<?php


namespace WabLab\Tools;

class DI implements \WabLab\Tools\Contracts\DI
{

    protected $map = [];

    public function make(string $className, array $constructorArguments = [])
    {
        $newObjArguments = [];
        $className = $this->map[$className] ?? $className;
        $class = new \ReflectionClass($className);
        $constructor = $class->getConstructor();
        $parameters = $constructor->getParameters(); /**@var $parameter \ReflectionParameter*/
        foreach($parameters as $parameter) {
            if( !isset($constructorArguments[$parameter->getName()]) ) {
                $parameterClass = $parameter->getClass();
                if($parameterClass) {
                    if(!$parameter->allowsNull()) {
                        $newObjArguments[$parameter->getName()] = $this->make('\\'.$parameterClass->getName());
                    } else {
                        $newObjArguments[$parameter->getName()] = null;
                    }
                } else {
                    $newObjArguments[$parameter->getName()] = null;
                }
            } else {
                $newObjArguments[$parameter->getName()] = $constructorArguments[$parameter->getName()];
            }
        }

        return $class->newInstanceArgs($newObjArguments);
    }

    public function map($left, $right) {
        $this->map[$left] = $right;
    }
}
