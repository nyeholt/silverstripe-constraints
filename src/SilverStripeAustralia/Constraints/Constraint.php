<?php

namespace SilverStripeAustralia\Constraints;

/**
 * Base class for all defined constraints defined
 *
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class Constraint {
	
	/**
	 * The field / key this constraint is applied to
	 *
	 * @var string
	 */
	protected $field;
	
	/**
	 * The object or array this should be applied to. 
	 * 
	 * @var mixed
	 */
	protected $object;
	
	/**
	 * In some cases, the constraint may be applied directly to a value
	 *
	 * @var mixed
	 */
	protected $value;
	
	/**
	 * Options for the constraints
	 * 
	 * @var array
	 */
	protected $options;
	
	public function __construct($appliedTo, $field = null, $options = null) {
		// if only provided a value and optional options
		if (count(func_get_args()) <= 2) {
			$this->value = $appliedTo;
			$this->options = $field;
		} else {
			$this->object = $appliedTo;
			$this->field = $field;
			$this->options = $options;
		}
	}
	
	protected function opt($name, $default = null) {
		return ($this->options && isset($this->options[$name])) ? $this->options[$name] : $default;
	}
	
	/**
	 * Does this constraint hold up for the current state of $this->object ?
	 * 
	 * @return boolean
	 */
	public function holds() {
		return false;
	}
	
	/**
	 * Get the message for when this constraint doesn't hold up
	 * 
	 * @return string
	 */
	public function message() {
		return get_class($this) . ' is invalid';
	}
	
	/**
	 * Gets the value this constraint is testing
	 * 
	 * @return mixed
	 */
	public function getValue() {
		if ($this->value) {
			return $this->value;
		}
		if ($this->object && $this->field) {
			$f = $this->field;
			if (is_array($this->object)) {
				return isset($this->object[$f]) ? $this->object[$f] : null;
			} else {
				return $this->object->$f;
			}
		}
	}
	
	/**
	 * Set the value being tested
	 * 
	 * @param mixed $value
	 * @return \SilverStripeAustralia\Constraints\Constraint
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
}
