<?php

/**
 * ErrorBag.php – validatron
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

namespace nxtlvlsoftware\validatron;

use function array_merge;

class FailureList {

	/** @var array */
	protected $failures = [];

	/**
	 * Add a failure to the list.
	 *
	 * @param \nxtlvlsoftware\validatron\Failure $failure
	 */
	public function add(Failure $failure) : void {
		$this->failures[$failure->getKey()] = $failure;
	}

	/**
	 * Check if a specific index has failed.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function failed(string $key) : bool {
		return isset($this->messages[$key]);
	}

	/**
	 * Get the count of all failures.
	 *
	 * @return int
	 */
	public function count() : int {
		return count($this->failures);
	}

	/**
	 * Get all the failures.
	 *
	 * @return array
	 */
	public function all() : array {
		return $this->failures;
	}

}