<?php
/**
 *  Basic Object Mapper
 *  @author Jakub Stejskal, jakub@stejsky.com
 *  @version 1.0
 */

namespace Stejsky\BaseModel;


use Nette\Object;
use Nette\Reflection\AnnotationsParser;

class BaseMapper extends Object {

	private $annotationForEncoding = 'map';

	/**
	 * @param BaseEntity $entity
	 * @return array - encoded array with annotations to store to DB
	 */
	public function encode(BaseEntity $entity)
	{
		$reflection = new \ReflectionClass($entity);
		$properties = $reflection->getProperties();
		$result = [];
		foreach ($properties as $property)
		{
			$annotations = AnnotationsParser::getAll($property);
			if (isset($annotations[$this->annotationForEncoding])) {
				$key = $annotations[$this->annotationForEncoding][0];
			} else {
				$key = $property->getName();
			}
			$value = $property->getValue($entity);
			$result[$key] = $value;
		}

		return $result;
	}

	public function decode($array)
	{

	}

	/**
	 * @param string $annotationForEncoding
	 */
	public function setAnnotationForEncoding($annotationForEncoding)
	{
		$this->annotationForEncoding = $annotationForEncoding;
	}
} 