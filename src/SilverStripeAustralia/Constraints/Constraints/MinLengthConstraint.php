<?php

namespace SilverStripeAustralia\Constraints\Constraints;

use SilverStripeAustralia\Constraints\Constraint;

/**
 * 
 *
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class MinLengthConstraint extends Constraint {
	
	public function holds() {
		$length = $this->opt('length', 0);
		
		$val = $this->getValue();
		
		return strlen($val) >= $length;
	}
	
}
