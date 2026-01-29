<?php
/**
 * Base Model Class
 * NGO Donor Management System
 */

abstract class Model {
    protected static $table;
    protected $attributes = [];
    protected $original = [];
    
    public function __construct(array $attributes = []) {
        $this->fill($attributes);
        $this->original = $this->attributes;
    }
    
    public function fill(array $attributes) {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
        return $this;
    }
    
    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }
    
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }
    
    public function __isset($name) {
        return isset($this->attributes[$name]);
    }
    
    public function getTable() {
        return static::$table;
    }
    
    public function save() {
        $db = Database::getInstance();
        $table = $this->getTable();
        
        if (isset($this->attributes['id'])) {
            // Update
            $sets = [];
            $values = [];
            foreach ($this->attributes as $key => $value) {
                if ($key !== 'id') {
                    $sets[] = "`$key` = ?";
                    $values[] = $value;
                }
            }
            $values[] = $this->attributes['id'];
            $sql = "UPDATE `$table` SET " . implode(', ', $sets) . " WHERE id = ?";
            $db->query($sql, $values);
        } else {
            // Insert
            $columns = array_keys($this->attributes);
            $placeholders = array_fill(0, count($columns), '?');
            $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";
            $db->query($sql, array_values($this->attributes));
            $this->attributes['id'] = $db->lastInsertId();
        }
        
        return $this;
    }
    
    public function delete() {
        if (!isset($this->attributes['id'])) {
            return false;
        }
        
        $db = Database::getInstance();
        $sql = "DELETE FROM `" . $this->getTable() . "` WHERE id = ?";
        return $db->query($sql, [$this->attributes['id']]);
    }
    
    public static function find($id) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM `" . static::$table . "` WHERE id = ? LIMIT 1";
        $result = $db->query($sql, [$id])->fetch();
        
        if ($result) {
            return new static($result);
        }
        return null;
    }
    
    public static function findBy($column, $value) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM `" . static::$table . "` WHERE `$column` = ? LIMIT 1";
        $result = $db->query($sql, [$value])->fetch();
        
        if ($result) {
            return new static($result);
        }
        return null;
    }
    
    public static function where($conditions = []) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM `" . static::$table . "`";
        
        if (!empty($conditions)) {
            $wheres = [];
            $values = [];
            foreach ($conditions as $column => $value) {
                $wheres[] = "`$column` = ?";
                $values[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $wheres);
        }
        
        $stmt = $db->query($sql, $values);
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        
        return new Collection($models);
    }
    
    public static function all() {
        $db = Database::getInstance();
        $sql = "SELECT * FROM `" . static::$table . "`";
        $stmt = $db->query($sql);
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        
        return new Collection($models);
    }
    
    public static function paginate($page = 1, $perPage = 10) {
        $db = Database::getInstance();
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM `" . static::$table . "` LIMIT ? OFFSET ?";
        $stmt = $db->query($sql, [$perPage, $offset]);
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        
        $totalSql = "SELECT COUNT(*) as count FROM `" . static::$table . "`";
        $total = $db->query($totalSql)->fetch()['count'];
        
        return [
            'data' => new Collection($models),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }
}
