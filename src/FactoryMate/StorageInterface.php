<?php

namespace DevDeclan\FactoryMate;

interface StorageInterface
{
    public function save($entity);
    public function delete($entity);
    public function load(string $className, $id);
    public function loadBy(string $className, string $property, $value);
    public function loadRandom(string $className);
    public function forEach(string $className);
    public function forEachBy(string $className, string $property, $value);
}
