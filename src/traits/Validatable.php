<?php

/**
 * Validatable.php – validatron
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

namespace nxtlvlsoftware\validatron\traits;

use nxtlvlsoftware\validatron\Collection;
use nxtlvlsoftware\validatron\IValidatable;
use nxtlvlsoftware\validatron\Rule;
use nxtlvlsoftware\validatron\rule\Optional;
use nxtlvlsoftware\validatron\rule\Repeats;
use nxtlvlsoftware\validatron\rule\Required;

trait Validatable {

	/** @var \nxtlvlsoftware\validatron\Collection[] */
	protected $collections = [];

	/** @var \nxtlvlsoftware\validatron\FailureList */
	protected $failures;

	/**
	 * @inheritdoc
	 */
	public function required(string $key) : Collection {
		return $this->addToCollection(new Required($this->retrieveCollection($key)));
	}

	/**
	 * @inheritdoc
	 */
	public function optional(string $key, $default) : Collection {
		return $this->addToCollection(
			(new Optional($this->retrieveCollection($key)))->fallback($default)
		);
	}

	/**
	 * @inheritdoc
	 */
	public function repeats(?string $key = null, ?int $min = null, ?int $max = null) : Collection {
		if($key === null) {

		} else {
			return $this->addToCollection(
				(new Repeats($this->retrieveCollection($key)))->limit($min, $max)
			);
		}
	}

	/**
	 * @param \nxtlvlsoftware\validatron\Rule $rule
	 *
	 * @return \nxtlvlsoftware\validatron\Collection
	 */
	protected function addToCollection(Rule $rule) : Collection {
		return $rule->done()->addRule($rule);
	}

	/**
	 * Retrieve an existing collection or construct a new one.
	 *
	 * @param string $key
	 *
	 * @return \nxtlvlsoftware\validatron\Collection
	 */
	protected function retrieveCollection(string $key) : Collection {
		if(!isset($this->collections[$key])) {
			$this->collections[$key] = new Collection($this->validatable(), $key);
		}

		return $this->collections[$key];
	}

	/**
	 * @return \nxtlvlsoftware\validatron\IValidatable
	 */
	abstract public function validatable() : IValidatable;

}