<?php

namespace SilverStripeAustralia\Constraints\Constraints;

use SilverStripeAustralia\Constraints\Constraint;

/**
 * Ensures a data object has a unique field
 * 
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class UniqueConstraint extends Constraint {
	
	public function holds() {
		$value = $this->getValue();
		
		$object = $this->object;
		
		if ($object instanceof DataObject) {
			$type = $object->ClassName;
			$existing = DataList::create($type)->filter($this->field, $value);
			
			if ($existing) {
				foreach ($existing as $match) {
					// if there's one with the value already, and the object with that value is NOT
					// this object, we have an issue
					if ($match && $match->ID && $match->ID != $object->ID) {
						return false;
					}
				}
			}
		}

		// this constraint doesn't matter if it's not a data object... do we error
		// or just return true (ie skip)
		return true;
	}
	

	public function message() {
		$item = $this->fieldLabel();
		
		$message = $this->opt('message', _t('Constraints.UNIQUE_VALUE', "%s must be unique"));
		return sprintf($message, $item);
	}
}
