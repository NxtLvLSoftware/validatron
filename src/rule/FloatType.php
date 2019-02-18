<?php

/**
 * FloatType.php – validatron
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
use function is_float;
use function is_numeric;
use function is_string;
use const FILTER_VALIDATE_FLOAT;

class FloatType extends Type {

	public const NOT_FLOAT = "NOT_FLOAT";

	protected function messages(TemplateList $container) : void {
		$container
			->template(self::NOT_FLOAT, '${key} must be a float.');
	}

	/**
	 * Validate a value is of a float type with a cast option.
	 *
	 * @param mixed $value
	 *
	 * @throws \nxtlvlsoftware\validatron\exception\ValidationFailedException
	 */
	public function validate(&$value) : void {
		if(!$this->cast and !is_float($value)) {
			$this->error(self::NOT_FLOAT); // value isn't an float and we were asked not to cast
		} elseif(is_string($value)) {
			if(($filtered = filter_var($value, FILTER_VALIDATE_FLOAT)) === false and !is_numeric($value)) {
				$this->error(self::NOT_FLOAT); // value can't be safely cast to float
			}
		}

		$value = (isset($filtered) ? $filtered : (float) $value);
	}

}