<?php

declare (strict_types=1);
namespace org\wplake\acf_views\vendors\LightSource\AcfGroups;

use Exception;
use org\wplake\acf_views\vendors\LightSource\AcfGroups\Interfaces\AcfGroupInterface;
use org\wplake\acf_views\vendors\LightSource\AcfGroups\Interfaces\CreatorInterface;
use org\wplake\acf_views\vendors\LightSource\AcfGroups\Interfaces\FieldInfoInterface;
use org\wplake\acf_views\vendors\LightSource\AcfGroups\Interfaces\GroupInfoInterface;
use ReflectionProperty;
class Creator implements CreatorInterface
{
    private array $creationChain;
    public function __construct()
    {
        $this->creationChain = [];
    }
    /**
     * @template T
     *
     * @param class-string<T> $groupClass
     *
     * @return T
     * @throws Exception
     */
    public function create(string $groupClass) : AcfGroupInterface
    {
        if (!\class_exists($groupClass) || !\in_array(AcfGroupInterface::class, \class_implements($groupClass), \true)) {
            throw new Exception('Fail to create a group instance, group class must implement AcfGroupInterface, class : ' . $groupClass);
        }
        if (\in_array($groupClass, $this->creationChain)) {
            throw new Exception('Fail to create a group instance.' . 'The next group constructor (' . $groupClass . ') will run a recursion, current classes chain is :' . \print_r($this->creationChain, \true));
        }
        $this->creationChain[] = $groupClass;
        try {
            $group = new $groupClass($this);
        } catch (Exception $exception) {
            throw new Exception('Fail to create instance of an acf group class, class : ' . $groupClass . ', issue : ' . $exception->getMessage());
        }
        if (!$group instanceof AcfGroupInterface) {
            throw new Exception('Acf group class must implements GroupInfoInterface, class :' . $groupClass);
        }
        \array_splice($this->creationChain, \count($this->creationChain) - 1, 1);
        return $group;
    }
}
