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

		$convertNewlines = $this->opt('convertnewlines', 1);
		$val = $convertNewlines ? str_replace("\r\n", "\n", $this->getValue()) : $this->getValue();

		return mb_strlen($val, 'utf-8') >= $length;
	}

	public function message() {
		$item = $this->fieldLabel();
		$length = $this->opt('length', 0);
		$message = $this->opt('message', _t('Constraints.MIN_LENGTH', "%s must be at least %s characters long"));
		return sprintf($message, $item, $length);
	}

}
