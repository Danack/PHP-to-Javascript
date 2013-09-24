<?php

namespace PHPToJavascript;

/**
 * I hate PHPs 'feature' of not throwing an error when you set a variable for a class that doesn't exist.
 * e.g. I meant to type:
 * $this->isValidated = true;
 * but accidentally type
 * $this->isVlidated = true;
 *
 * As you spend an hour trying to find out why isValidated isn't being set. This trait turns all bad
 * get and set calls on non-existent variables into exceptions.
 */

trait SafeAccess {
	public function __set($name, $value) {
		throw new \Exception("Property [$name] doesn't exist for class [".__CLASS__."] so can set it");
	}
	public function __get($name) {
		throw new \Exception("Property [$name] doesn't exist for class [".__CLASS__."] so can get it");
	}
}
