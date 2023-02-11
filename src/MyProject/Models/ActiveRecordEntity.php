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
        $filteredProperties = array_filter($mappedProperties);
        $columns = [];
        $paramsNames = [];
        $paramsValues = [];
        foreach ($filteredProperties as $columnName => $value) {
            $columns[] = '`'.$columnName.'`';
            $paramName = ':' . $columnName;
            $paramsNames[] = $paramName;
            $paramsValues[$paramName] = $value;
        }

        $columnsViaSemicolon = implode(',', $columns);
        $paramsNamesViaSemicolon = implode(',', $paramsNames);
        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . $columnsViaSemicolon . ') VALUES (' . $paramsNamesViaSemicolon . ');';
        $db = DB::getInstance();
        $db->query($sql, $paramsValues , static::class);
        $this->id = $db->getLastInsertId();
        $this->refresh();
    }
    public function refresh(): void
    {
       /* $objFromDb = static::getById($this->id);

        $properties = get_object_vars($objFromDb);

        foreach ($properties as $key=>$value) {
            $this->$key = $value;
        }*/

        //или

        $objectFromDb = static::getById($this->id);
        $reflector = new \ReflectionObject($objectFromDb);
        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $this->$propertyName = $property->getValue($objectFromDb);
        }
    }

    public function delete(): void
    {
        $db = DB::getInstance();
        $db->query(
            'DELETE FROM `' . static::getTableName() . '` WHERE id = :id',
            [':id' => $this->id]
        );
        $this->id = null;
    }



    abstract protected static function getTableName():string;
}


?>