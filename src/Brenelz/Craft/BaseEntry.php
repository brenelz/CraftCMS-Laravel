<?php namespace Brenelz\Craft;

use Brenelz\Craft\Service as Craft;

class BaseEntry  {
	protected $craft;
	protected $criteria;
	protected $criteriaAdaptor;
	protected $type = 'Entry';

	public function __construct(Craft $craft) {
		$this->craft = $craft;
		$this->criteria = $this->craft->elements->getCriteria($this->type);
		$this->criteria->section = $this->section;


		$this->criteriaAdaptor = new CriteriaAdaptor($this->criteria);
	}

	public function criteria() {
		return $this->criteriaAdaptor;
	}

	public function __call($name, $arguments) {
		$result = call_user_func_array(array($this->criteriaAdaptor, $name), $arguments);

		if ($result === $this->criteriaAdaptor) return $this;
		return $result;

	}

	public function find($id)
	{
		$item = $this->craft->elements->getElementById($id, $this->type);
		if( strtolower($item->section) == strtolower($this->section)) return $item;

	}

}