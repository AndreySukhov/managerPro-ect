<?php

namespace AppBundle\DataFixtures\Traits;

trait OrderedFixtureTrait
{
    public function getOrder()
    {
        $reflection = new \ReflectionClass($this);
        $className = $reflection->getShortName();

        preg_match('/Load_(\d+)_(.*?)/', $className, $matches);

        return isset($matches[1]) ? (int) $matches[1] : 0;
    }
}
