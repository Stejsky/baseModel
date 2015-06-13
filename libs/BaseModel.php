<?php
/**
 *  General BaseModel library
 *  @author Jakub Stejskal, jakub@stejsky.com
 *  @version 1.0
 */

namespace Stejsky\BaseModel;


class BaseModel {

	/**
	* @var \Nette\Database\Context @inject
	*/
	public $database;

	public function getTable()
	{
		if (static::TABLE_NAME) {
			return $this->database->table(static::TABLE_NAME);
		} else {
			throw new \Exception('Není definován název tabulky');
		}
	}

	public function getItem($id)
	{
		return $this->getTable()->get($id);
	}

	public function addItem(BaseEntity $entity)
	{

	}


} 