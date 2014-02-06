<?php

namespace SilverStripeAustralia\Constraints\Extensions;

/**
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class ConstraintsExtension extends \DataExtension {
	
	/**
	 *
	 * @var Injector
	 */
	public $injector;
	
	public function validate(\ValidationResult $validationResult) {
		
		$constraints = $this->getConstraints();
		
		foreach ($constraints as $constraint) {
			if (!$constraint->holds()) {
				$validationResult->error($constraint->message());
			}
		}
	}

	public function getConstraints() {
		// evaluate any configured constraints
		$constraints = $this->owner->config()->get('constraints');
		
		$allConstraints = array();
		
		if (count($constraints)) {
			foreach ($constraints as $fieldName => $info) {
				// if we've only given one constraint, it'll be a string, so make it an array
				if (is_string($info)) {
					$info = array($info);
				}
				foreach ($info as $constraintClass => $config) {
					// allow plain classes, without arrays
					if (is_numeric($constraintClass)) {
						$constraintClass = $config;
						$config = array();
					}
					if (!is_array($config)) {
						parse_str($config, $arr);
						$config = $arr;
					}

					if (!class_exists($constraintClass)) {
						$constraintClass = 'SilverStripeAustralia\\Constraints\Constraints\\' . $constraintClass;
					}
					
					$constraint = $this->injector->create($constraintClass, $this->owner, $fieldName, $config);
					
					$allConstraints[] = $constraint;
				}
			}
		}

		return $allConstraints;
	}
}
