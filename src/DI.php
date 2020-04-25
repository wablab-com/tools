<?php


namespace WabLab\Tools;

class DI implements \WabLab\Tools\Contracts\DI
{

    protected $map = [];
    protected $loopDetection = [];

    public function make(string $className, array $constructorArguments = [])
    {
        $newObjArguments = [];
        $mappedClass = $this->getMappedClass($className);

        $this->loopDetectionCheck($mappedClass);

        $this->loopDetection[$mappedClass] = $mappedClass;
        $class = new \ReflectionClass($mappedClass);
        $constructor = $class->getConstructor();
        if($constructor) {
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
        }

        $newObj = $class->newInstanceArgs($newObjArguments);
        unset($this->loopDetection[$mappedClass]);
        return $newObj;
    }

    public function map($left, $right) {
        $this->map[trim($left, '\\')] = trim($right, '\\');
    }

    public function getMappedClass($className) {
        $processedClassName = trim($className, '\\');
        return '\\'.($this->map[$processedClassName] ?? $processedClassName);
    }

    public function loopDetectionCheck($className) {
        if(isset($this->loopDetection[$className])) {
            $loop = array_values($this->loopDetection);
            $loop[] = $className;

            $this->loopDetection = [];
            throw new \Exception('New Instance Loop Error: '.implode(' >> ', $loop));
        }
    }

}

