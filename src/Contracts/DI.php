<?php


namespace WabLab\Tools\Contracts;


interface DI
{
    public static function make(string $className, array $constructorArguments = []);
}