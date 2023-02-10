<?php 
namespace MyProject\Models;
use MyProject\Services\DB;


abstract class ActiveRecordEntity
{
	 /** @var int */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

	public function __set($name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

	private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

	public static function findAll():array 
    {
        $db = DB::getInstance();
        return $db->query(
        	'SELECT * FROM `'.static::getTableName().'`;',
        	[],
        	static::class);
    }
    public static function getById(int $id): ?self
    {
    	$db = DB::getInstance();
    	$entities = $db->query(
    		'SELECT * FROM '.static::getTableName().' WHERE id=:id',
    		[':id'=>$id],
    		static::class
    	);
    	return $entities ? $entities[0] : null;
    }

    public function save(): void
    {
        $mappedProperties = $this->mappedProperties();
        //var_dump($mappedProperties);
        if ($this->id !== null) 
        {
            $this->update($mappedProperties);
        }
        else
        {
            $this->insert($mappedProperties);
        }
    }

    public function mappedProperties():array
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $mappedProperties[$propertyName] = $this->$propertyName;
        }
        return $mappedProperties;
    } 

    public function update(array $mappedProperties):void
    {
        $columnParam = [];
        $paramValue = [];
        $index = 1;
        foreach ($mappedProperties as $column => $value) {
            $param = ':param' . $index;
            $columnParam[] = $column.' = '.$param;
            $paramValue[$param] = $value; 
            $index++;
        }
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(',', $columnParam) . ' WHERE id = '.$this->id;
        $db = DB::getInstance();
        $db->query($sql, $paramValue, static::class);

    }
    
    public function insert(array $mappedProperties):void
    {
        
    }



    abstract protected static function getTableName():string;
}


?>