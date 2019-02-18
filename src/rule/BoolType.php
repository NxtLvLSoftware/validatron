<?php

/**
 * BoolType.php – validatron
 *
 * Copyright (C) 2019 Jack Noordhuis
 *
 * @author Jack Noordhuis
 *
 * This is free and unencumbered software released into the public domain.
 *
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any means.
 *
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * For more information, please refer to <http://unlicense.org/>
 *
 */

declare(strict_types=1);

namespace nxtlvlsoftware\validatron\rule;

use nxtlvlsoftware\validatron\TemplateList;
use function filter_var;
use function is_bool;
use function is_string;
use const FILTER_NULL_ON_FAILURE;
use const FILTER_VALIDATE_BOOLEAN;

class BoolType extends Type {

	public const NOT_BOOL = "NOT_BOOL", NOT_BOOLEAN = "NOT_BOOL";

	protected function messages(TemplateList $container) : void {
		$container
			->template(self::NOT_BOOL, '${key} must be a bool.');
	}

	/**
	 * Validate a value is of a bool type with a cast option.
	 *
	 * @param mixed $value
	 *
	 * @throws \nxtlvlsoftware\validatron\exception\ValidationFailedException
	 */
	public function validate(&$value) : void {
		if(($this->cast and !is_bool($value)) or !(is_bool($value) or is_string($value))) {
			$this->error(self::NOT_BOOL); // value isn't an bool and we were asked not to cast
		} elseif(($corrected = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) === null) {
			$this->error(self::NOT_BOOL); // value can't be safely cast to bool
		}

		$value = isset($corrected) ? $corrected : $value;
	}

}