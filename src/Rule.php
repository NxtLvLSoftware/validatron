<?php

/**
 * Rule.php – validatron
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

use nxtlvlsoftware\validatron\exception\ValidationFailedException;
use ReflectionObject;

abstract class Rule {

	/** @var \nxtlvlsoftware\validatron\Collection */
	private $collection;

	/** @var \nxtlvlsoftware\validatron\TemplateList */
	private $messages;

	public function __construct(Collection $collection) {
		$this->collection = $collection;
		$this->messages = new TemplateList();
	}

	/**
	 * Add all the messages used by this rule into the message container.
	 *
	 * @param \nxtlvlsoftware\validatron\TemplateList $container
	 *
	 * @return mixed
	 */
	protected function messages(TemplateList $container) : void {

	}

	/**
	 * Perform validation on the given input and return the first issue found.
	 *
	 * @param mixed &$value
	 *
	 * @throws \nxtlvlsoftware\validatron\exception\ValidationFailedException
	 */
	abstract public function validate(&$value) : void;

	/**
	 *
	 *
	 * @param string $reason
	 *
	 * @throws \nxtlvlsoftware\validatron\exception\ValidationFailedException
	 */
	protected function error(string $reason) : void {
		$this->messages($this->messages); //only put the error messages into the list if we need them
		throw new ValidationFailedException($this->messages, $reason);
	}

	/**
	 * Get the name for this rule.
	 *
	 * @return string
	 */
	public function name() : string {
		return (new ReflectionObject($this))->getShortName();
	}

	/**
	 * Returns back to the parent collection scope.
	 *
	 * @return \nxtlvlsoftware\validatron\Collection
	 */
	public function done() : Collection {
		return $this->collection;
	}

	/**
	 * Returns back to the parent validation scope.
	 *
	 * @return \nxtlvlsoftware\validatron\IValidatable
	 */
	public function new() : IValidatable {
		return $this->collection->new();
	}

}