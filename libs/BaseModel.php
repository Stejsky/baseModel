<?php
/**
 *  General BaseModel library
 *  @author Jakub Stejskal, jakub@stejsky.com
 *  @version 1.0
 */

namespace Stejsky\BaseModel;

use Nette\Database\Context;
use Nette\Object;

abstract class BaseModel extends Object {

	protected $database;

	protected $baseMapper;

	public function __construct(Context $database, BaseMapper $baseMapper)
	{
		$this->baseMapper  = $baseMapper;
		$this->database = $database;
	}

	public function getTable()
	{
		$tableName = $this->getTableName();
		return $this->database->table($tableName);
	}

	public function getItem($id)
	{
		return $this->getTable()->get($id);
	}

	public function getAllItems()
	{
		return $this->getTable()->fetchAll();
	}

	public function addItem(BaseEntity $entity)
	{
		$array = $this->baseMapper->encode($entity);
		$this->getTable()->insert($array);
	}

	private static function getTableName()
	{
		if (defined('TABLE_NAME')) {
			return self::TABLE_NAME;
		} else {
			$tableClass = get_called_class();
			$explodedArray = explode("\\", $tableClass);
			$tableName = end($explodedArray);
			$result = strtolower(preg_replace('/\B([A-Z])/', '-$1', $tableName));
			$explodedArray = explode('-', $result);
			array_pop($explodedArray);
			$result = implode('-', $explodedArray);
			$result = str_replace('-', '_', $result);
			return $result;
		}
	}

	public function deleteAll()
	{
		$this->getTable()->delete();
	}


} 