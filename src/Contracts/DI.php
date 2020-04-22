<?php


namespace WabLab\Tools\Contracts;


interface DI
{
    public function make(string $className, array $constructorArguments = []);
}