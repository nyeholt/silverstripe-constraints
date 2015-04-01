<?php

namespace SilverStripeAustralia\Constraints\Constraints;

use SilverStripeAustralia\Constraints\Constraint;

/**
 * 
 *
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class RegexConstraint extends Constraint {
	
	public function holds() {
		$regex = $this->opt('regex');
		if (!$regex) {
			return true;
		}

		$negate = $this->opt('negate', false);
		$modifiers = $this->opt('modifiers', '');
		
		$val = $this->getValue();
		
		if (!strlen($val)) {
			return true;
		}
		
		$fullRegex = "/$regex/$modifiers";
		
		$match = (boolean) preg_match($fullRegex, $val);
		
		return $negate ? !$match : $match;
	}
	
	public function message() {
		$item = $this->fieldLabel();
		$chars = $this->opt('regex', 0);
		$message = $this->opt('message', _t('Constraints.WHITELIST', "Only text that matches %s can be used for \"%s\""));
		return sprintf($message, $chars, $item);
	}
}
