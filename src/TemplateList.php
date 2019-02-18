<?php

/**
 * MessageContainer.php – validatron
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

use function array_keys;
use function sprintf;
use function str_replace;

/**
 * Used to maintain a list of message templates and parameters to build a complete message.
 */
class TemplateList {

	/** @var string[] */
	protected $templates = [];

	/** @var string[] */
	protected $parameters = [];

	public function __construct(array $messages = []) {
		$this->templates = $messages;
	}

	/**
	 * Add a new message template to the container.
	 *
	 * @param string $key
	 * @param string $template
	 *
	 * @return \nxtlvlsoftware\validatron\TemplateList
	 */
	public function template(string $key, string $template) : TemplateList {
		$this->templates[$key] = $template;
		return $this;
	}

	/**
	 * Add a parameter to the container to be used in building a message.
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return \nxtlvlsoftware\validatron\TemplateList
	 */
	public function parameter(string $name, string $value) : TemplateList {
		$this->parameters[sprintf('${%s}', $name)] = $value;
		return $this;
	}

	/**
	 * Build a complete message from a template and the containers parameters.
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function message(string $key) : string {
		return str_replace(array_keys($this->parameters), $this->parameters, $this->templates[$key] ?? "");
	}

}