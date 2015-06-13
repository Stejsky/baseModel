<?php
/**
 *  Base Entity
 *  @author Jakub Stejskal, jakub@stejsky.com
 *  @version 1.0
 */

namespace Stejsky\BaseModel;

use Nette\Object;

class BaseEntity extends Object {

	/**
	 * @var int
	 * @map login_user_id
	 */
	public $id;

	public $name;

} 