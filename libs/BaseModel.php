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

	protected function getTable()
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