<?php
/**
 * Base Model Class
 * NGO Donor Management System
 * Supports MySQL and PostgreSQL
 */

abstract class Model {
    protected static string $table;
    protected array $attributes = [];
    protected array $original = [];
    
    public function __construct(array $attributes = []) {
        $this->fill($attributes);
        $this->original = $this->attributes;
    }
    
    public function fill(array $attributes): self {
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
    
    public function getTable(): string {
        return static::$table;
    }
    
    private function getDriver(): string {
        return Database::getInstance()->getDriver();
    }
    
    private function quoteIdentifier($identifier): string {
        if ($this->getDriver() === 'pgsql') {
            return '"' . $identifier . '"';
        }
        return '`' . $identifier . '`';
    }
    
    public function save(): self {
        $db = Database::getInstance();
        $table = $this->getTable();
        
        if (isset($this->attributes['id'])) {
            // Update
            $sets = [];
            $values = [];
            foreach ($this->attributes as $key => $value) {
                if ($key !== 'id') {
                    $sets[] = $this->quoteIdentifier($key) . " = ?";
                    $values[] = $value;
                }
            }
            $values[] = $this->attributes['id'];
            $sql = "UPDATE " . $this->quoteIdentifier($table) . " SET " . implode(', ', $sets) . " WHERE id = ?";
            $db->query($sql, $values);
        } else {
            // Insert
            $columns = array_keys($this->attributes);
            $placeholders = array_fill(0, count($columns), '?');
            $sql = "INSERT INTO " . $this->quoteIdentifier($table) . " (" . implode(', ', array_map([$this, 'quoteIdentifier'], $columns)) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $db->query($sql, array_values($this->attributes));
            $this->attributes['id'] = $db->lastInsertId();
        }
        
        return $this;
    }
    
    public function delete(): bool {
        if (!isset($this->attributes['id'])) {
            return false;
        }
        
        $db = Database::getInstance();
        $sql = "DELETE FROM " . $this->quoteIdentifier($this->getTable()) . " WHERE id = ?";
        return (bool) $db->query($sql, [$this->attributes['id']]);
    }
    
    public static function find($id) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM " . static::quote(static::$table) . " WHERE id = ? LIMIT 1";
        $result = $db->query($sql, [$id])->fetch();
        
        if ($result) {
            return new static($result);
        }
        return null;
    }
    
    public static function findBy($column, $value) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM " . static::quote(static::$table) . " WHERE " . static::quote($column) . " = ? LIMIT 1";
        $result = $db->query($sql, [$value])->fetch();
        
        if ($result) {
            return new static($result);
        }
        return null;
    }
    
    private static function quote($identifier): string {
        $db = Database::getInstance();
        if ($db->getDriver() === 'pgsql') {
            return '"' . $identifier . '"';
        }
        return '`' . $identifier . '`';
    }
    
    public static function where($conditions = []): Collection {
        $db = Database::getInstance();
        $sql = "SELECT * FROM " . static::quote(static::$table);
        
        $values = [];
        if (!empty($conditions)) {
            $wheres = [];
            foreach ($conditions as $column => $value) {
                $wheres[] = static::quote($column) . " = ?";
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
    
    public static function all(): Collection {
        $db = Database::getInstance();
        $sql = "SELECT * FROM " . static::quote(static::$table);
        $stmt = $db->query($sql);
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        
        return new Collection($models);
    }
    
    public static function paginate(int $page = 1, int $perPage = 10): array {
        $db = Database::getInstance();
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM " . static::quote(static::$table) . " LIMIT ? OFFSET ?";
        $stmt = $db->query($sql, [$perPage, $offset]);
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        
        $totalSql = "SELECT COUNT(*) as count FROM " . static::quote(static::$table);
        $total = $db->query($totalSql)->fetch()['count'];
        
        return [
            'data' => new Collection($models),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }
    
    public function update(array $data): self {
        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
        return $this->save();
    }
    
    public static function create(array $attributes) {
        $model = new static($attributes);
        $model->save();
        return $model;
    }
}
