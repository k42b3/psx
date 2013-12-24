<?php
/*
 * psx
 * A object oriented and modular based PHP framework for developing
 * dynamic web applications. For the current version and informations
 * visit <http://phpsx.org>
 *
 * Copyright (c) 2010-2013 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This file is part of psx. psx is free software: you can
 * redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 *
 * psx is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with psx. If not, see <http://www.gnu.org/licenses/>.
 */

namespace PSX\Handler\Manager;

use PDO;
use PSX\Handler\HandlerManagerInterface;

/**
 * PdoManager
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @link    http://phpsx.org
 */
class PdoManager implements HandlerManagerInterface
{
	/**
	 * @var PDO
	 */
	protected $pdo;

	protected $_container;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function getHandler($className)
	{
		if(!isset($this->_container[$className]))
		{
			$this->_container[$className] = new $className($this->pdo);
		}

		return $this->_container[$className];
	}
}