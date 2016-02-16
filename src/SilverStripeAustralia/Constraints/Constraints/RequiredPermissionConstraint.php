<?php

namespace SilverStripeAustralia\Constraints\Constraints;

use SilverStripeAustralia\Constraints\Constraint;

/**
 *
 * Used when a user must have a specific permission to be allowed to edit a specific
 * field.
 *
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class RequiredPermissionConstraint extends Constraint {
	public function holds() {
		$perm = $this->opt('permission');
		if (!$perm) {
			return true;
		}
		$object = $this->object;
		if ($object instanceof \DataObject) {
			$changed = $this->object->isChanged($this->field, 2);

			if ($changed) {
				return \Permission::check($perm);
			}
		}
		return true;
	}
}
