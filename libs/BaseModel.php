<?php
/**
 *  General BaseModel library
 *  @author Jakub Stejskal, jakub@stejsky.com
 *  @version 1.0
 */

namespace Stejsky\BaseModel;

use Nette\Database\Context;
use Nette\Object;

class BaseModel extends Object {

	/** @var  Context */
	protected $database;

	/** @var  BaseMapper */
	protected $baseMapper;

	protected $entity;

	protected $entityName;

	public function __construct(Context $database, BaseMapper $baseMapper)
	{
		$this->baseMapper  = $baseMapper;
		$this->database = $database;
		$this->entity = new $this->entityName;
	}

	public function getTable()
	{
		$tableName = $this->getTableName();
		return $this->database->table($tableName);
	}

	public function getItem($id)
	{
		return $this->decode($this->getTable()->get($id));
	}

	protected function decode($rows)
	{
		return $this->baseMapper->decode($rows, $this->entity);
	}

	public function getAllItems()
	{
		return $this->decode($this->getTable()->fetchAll());
	}

	public function addItem(BaseEntity $entity)
	{
		$array = $this->encode($entity);
		return $this->decode($this->getTable()->insert($array));
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

	public function deleteItem($id)
	{
		$this->getTable()->where('id', $id)->delete();
	}

	public function getItemInArray($id)
	{
		return $this->getTable()->get($id);
	}

	public function updateItem(BaseEntity $entity)
	{
		$array = $this->encode($entity);
		$this->getTable()->where('id', $entity->id)->update($array);
	}

	protected function encode($entity)
	{
		return $this->baseMapper->encode($entity);
	}

	public function getPairs($value = null, $value1 = null)
	{
		return $this->getTable()->fetchPairs($value, $value1);
	}

	public function getWhere($array)
	{
		return $this->decode($this->getTable()->where($array)->fetchAll());
	}

	public function getSingleWhere($array)
	{
		return $this->decode($this->getTable()->where($array)->fetch());
	}

} 