<?php
namespace LarpManager\Repository;
 
class LikeExpression
{
    protected $column;
    protected $key;
    protected $value;
        
    public function __construct($column, $value) {
        $this->column = $column;
        $this->key = "key" . preg_replace("/[^a-zA-Z]/", "_", $column);
        $this->value = $value;
    }
    
    public function apply($qb) {
        $qb->andWhere($this->column  . " like :" . $this->key);
        $qb->setParameter($this->key, $this->value);
    }
}

?>