<?php namespace Brenelz\Craft;

use Illuminate\Support\Collection;

class CriteriaAdaptor {

	protected $criteria;

	public function __construct($criteria) {
		$this->criteria = $criteria;
	}

	public function get() {
		return new Collection($this->criteria->find());
	}

	public function all() {
		return $this->get();
	}

	public function __call($name, $arguments)
    {
    	return new self(call_user_func_array(array($this->criteria, $name), $arguments));
    }

}
