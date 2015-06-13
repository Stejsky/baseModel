<?php
/**
 *  Basic Object Mapper
 *  @author Jakub Stejskal, jakub@stejsky.com
 *  @version 1.0
 */

namespace Stejsky\BaseModel;


class BaseMapper {

	public function encode(BaseEntity $entity)
	{
		$reflection = new \ReflectionClass($entity);
		$methods = get_object_vars($entity);
		foreach ($methods as $method)
		{
			dump ($method);
		}
			die;
	}

} 