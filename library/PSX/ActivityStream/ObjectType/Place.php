<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\ActivityStream\ObjectType;

use PSX\ActivityStream\Object;

/**
 * Place
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Place extends Object
{
	protected $position;
	protected $address;

	public function __construct()
	{
		$this->objectType = 'place';
	}

	/**
	 * @param \PSX\ActivityStream\Position $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}
	
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param \PSX\ActivityStream\Address $address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
}
