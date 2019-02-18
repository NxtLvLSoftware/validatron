<?php

/**
 * Collection.php – validatron
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
use nxtlvlsoftware\validatron\rule\ArrayType;
use nxtlvlsoftware\validatron\rule\BoolType;
use nxtlvlsoftware\validatron\rule\FloatType;
use nxtlvlsoftware\validatron\rule\IntType;
use nxtlvlsoftware\validatron\rule\StringType;
use nxtlvlsoftware\validatron\utils\UndefinedValue;

/**
 * Represents a collection of validation rules.
 */
class Collection {

	/** @var \nxtlvlsoftware\validatron\IValidatable */
	protected $validatable;

	/**
	 * The key to validate
	 *
	 * @var string
	 */
	protected $key;

	/** @var Rule[] */
	protected $rules = [];

	/** @var \nxtlvlsoftware\validatron\TemplateList */
	protected $messages;

	public function __construct(IValidatable $validatable, string $key) {
		$this->validatable = $validatable;
		$this->key = $key;
	}

	/**
	 * @return \nxtlvlsoftware\validatron\rule\ArrayType
	 */
	public function array() : ArrayType {
		$this->addRule($rule = new ArrayType($this));
		return $rule;
	}

	/**
	 * @return \nxtlvlsoftware\validatron\rule\BoolType
	 */
	public function bool() : BoolType {
		$this->addRule($rule = new BoolType($this));
		return $rule;
	}

	/**
	 * @return \nxtlvlsoftware\validatron\rule\FloatType
	 */
	public function float() : FloatType {
		$this->addRule($rule = new FloatType($this));
		return $rule;
	}

	/**
	 * @return \nxtlvlsoftware\validatron\rule\StringType
	 */
	public function string() : StringType {
		$this->addRule($rule = new StringType($this));
		return $rule;
	}

	/**
	 * @return \nxtlvlsoftware\validatron\rule\IntType
	 */
	public function int() : IntType {
		$this->addRule($rule = new IntType($this));
		return $rule;
	}

	/**
	 * Returns back to the parent validation scope.
	 *
	 * @return \nxtlvlsoftware\validatron\IValidatable
	 */
	public function new() : IValidatable {
		return $this->validatable;
	}

	/**
	 * @param \nxtlvlsoftware\validatron\Rule $rule
	 *
	 * @return \nxtlvlsoftware\validatron\Collection
	 */
	public function addRule(Rule $rule) : Collection {
		$this->rules[$rule->name()] = $rule;
		return $this;
	}

	public function validate(FailureList $failures, ValueList $input, ValueList $output) {
		$value = $input->has($this->key) ? $input->get($this->key) : new UndefinedValue;

		foreach($this->rules as $rule) {
			try {
				$rule->validate($value);
			} catch(ValidationFailedException $e) {
				$failures->add(new Failure(
					$this->key,
					$rule,
					$e->getReason([
						"key" => $this->key
					])
				));
			}
		}

		if(count($failures->get($this->key)) === 0) {
			$output->set($this->key, $value);
		}
	}

}