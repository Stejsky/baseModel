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
	private $annotationForIgnore = 'ignore';

	/**
	 * @param BaseEntity $entity
	 * @return array - encoded array with annotations to store to DB
	 */
	public function encode(BaseEntity $entity)
	{
		$reflection = new \ReflectionClass($entity);
		$properties = $reflection->getProperties();
		$result = array();
		foreach ($properties as $property)
		{
			$annotations = AnnotationsParser::getAll($property);
			if (isset($annotations[$this->annotationForEncoding])) {
				$key = $annotations[$this->annotationForEncoding][0];
			} elseif(isset($annotations[$this->annotationForIgnore])) {
				continue;
			} else {
				$key = $property->getName();
			}
			$value = $property->getValue($entity);
			$result[$key] = $value;
		}

		return $result;
	}

	/**
	 * @param $array - encoded object|array to create new object
	 * @param $object - new instance of entity, that should be filled
	 * @return $object
	 */
	public function decode($array, $object)
	{
		$reflection = new \ReflectionClass($object);
		$properties = $reflection->getProperties();
		foreach ($properties as $property)
		{
			$annotations = AnnotationsParser::getAll($property);
			$annotation = (isset($annotations[$this->annotationForEncoding]) ? $annotations[$this->annotationForEncoding] : null);
			if (isset($annotation)) {
				if (isset($array[$annotation[0]])) {
					$name = $property->getName();
					$value = (isset($array[$annotation[0]]) ? $array[$annotation[0]] : null);
					if ($value) {
						$object->$name = $value;
					}
				}
			} else {
				$name = $property->getName();
				$value = (isset($array[$name]) ? $array[$name] : null);
				if ($value) {
					$object->$name = $value;
				}

			}
		}

		return $object;
	}

	public function decodeList($array, $objectName)
	{
		$result = array();
		foreach ($array as $singleArray)
		{
			$result[] = $this->decode($singleArray, new $objectName);
		}
		return $result;
	}

	/**
	 * @param string $annotationForEncoding
	 */
	public function setAnnotationForEncoding($annotationForEncoding)
	{
		$this->annotationForEncoding = $annotationForEncoding;
	}

	/**
	 * @param string $annotationForIgnore
	 */
	public function setAnnotationForIgnore($annotationForIgnore)
	{
		$this->annotationForIgnore = $annotationForIgnore;
	}
} 