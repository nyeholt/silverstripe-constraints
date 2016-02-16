<?php

namespace SilverStripeAustralia\Constraints\Constraints;

use SilverStripeAustralia\Constraints\Constraint;

/**
 * Ensure a field is less than a certain number of characters long
 *
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class MaxLengthConstraint extends Constraint {
	public function holds() {
		$length = $this->opt('length', 0);

		// do we convert newlines to single characters for counting?
		$convertNewlines = $this->opt('convertnewlines', 1);
		$val = $convertNewlines ? str_replace("\r\n", "\n", $this->getValue()) : $this->getValue();

		$l = mb_strlen($val, 'utf-8');

		return $l <= $length;
	}

	public function message() {
		$item = $this->fieldLabel();
		$length = $this->opt('length', 0);
		$message = $this->opt('message', _t('Constraints.MAX_LENGTH', "%s must be less than %s characters long"));
		return sprintf($message, $item, $length);
	}
}
