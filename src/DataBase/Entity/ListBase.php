<?php

declare(strict_types=1);

namespace App\DataBase\Entity;

class ListBase
{

    
    /**
     * @var int
     */
    private $numItems;

    /**
     * @var \ArrayIterator
     */
    private $list;

    public function __construct(int $numItems, \ArrayIterator $list) {
        $this->numItems = $numItems;
        $this->list = $list;
    }

    public function getNumItems(): ?int
    {
        return $this->numItems;
    }
    
    public function setNumItems(int $numItems)
    {
        $this->numItems = $numItems;
    }

    public function getList(): ?\ArrayIterator 
    {
        return $this->list;
    }

    public function setList(\ArrayIterator $list)
    {
        $this->list = $list;
    }
}