<?php
/**
 * Collection Class for handling arrays of models
 * NGO Donor Management System
 */

class Collection implements IteratorAggregate, Countable {
    protected $items = [];
    
    public function __construct(array $items = []) {
        $this->items = $items;
    }
    
    public static function make(array $items = []) {
        return new static($items);
    }
    
    public function all() {
        return $this->items;
    }
    
    public function count(): int {
        return count($this->items);
    }
    
    public function getIterator(): Traversable {
        return new ArrayIterator($this->items);
    }
    
    public function first() {
        return reset($this->items) ?: null;
    }
    
    public function map(callable $callback) {
        $result = [];
        foreach ($this->items as $key => $item) {
            $result[$key] = $callback($item, $key);
        }
        return new static($result);
    }
    
    public function filter(callable $callback = null) {
        if ($callback === null) {
            return new static(array_filter($this->items));
        }
        
        $result = [];
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key)) {
                $result[$key] = $item;
            }
        }
        return new static($result);
    }
    
    public function take($limit = null) {
        if ($limit === null) {
            return new static($this->items);
        }
        return new static(array_slice($this->items, 0, $limit));
    }
    
    public function pluck($value, $key = null) {
        $result = [];
        foreach ($this->items as $item) {
            if (is_object($item)) {
                $itemValue = $item->$value;
            } else {
                $itemValue = $item[$value];
            }
            
            if ($key === null) {
                $result[] = $itemValue;
            } else {
                if (is_object($item)) {
                    $result[$item->$key] = $itemValue;
                } else {
                    $result[$item[$key]] = $itemValue;
                }
            }
        }
        return new static($result);
    }
    
    public function toArray() {
        return array_map(function ($item) {
            if ($item instanceof Model) {
                return $item->attributes;
            }
            return $item;
        }, $this->items);
    }
    
    public function isEmpty() {
        return empty($this->items);
    }
    
    public function isNotEmpty() {
        return !empty($this->items);
    }
}
