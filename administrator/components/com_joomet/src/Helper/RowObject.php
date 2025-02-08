<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Helper;

defined('_JEXEC') or die;

enum RowType: string
{
	case TRANSLATION = 'TRANSLATION';
	case COMMENT = 'COMMENT';
	case EMPTY = 'EMPTY';
}


class RowObject
{

	public string $original;
	public string|null $constant;
	public string $content;
	public array $errors;
	public array $warnings;
	public array $infos;
	public int $rowNum;
	public RowType $recognisedRowType;

	/**
	 * @param   string  $original
	 * @param   int     $rowNum
	 *
	 * @since 1.0.0
	 */
	public function __construct(string $original, int $rowNum = 1)
	{
		$this->original = $original;
		list($this->constant, $this->content) = $this->getKeyAndValue($original);
		$this->errors            = [];
		$this->warnings          = [];
		$this->infos             = [];
		$this->rowNum            = $rowNum;
		$this->recognisedRowType = $this->identifyRowType();
	}

	private function getKeyAndValue(string $row): array
	{
		$row = trim($row);
		if (empty($row))
		{
			return [null, ""];
		}
		// Key and Value are separated by "="
		$parts = explode('=', $row);
		if (count($parts) < 2)
		{
			return [null, $row];
		}
		else if (count($parts) > 2)
		{
			$constant    = trim($parts[0]);
			$translation = trim(implode('=', array_slice($parts, 1)));
		}
		else
		{
			$constant    = trim($parts[0]);
			$translation = trim($parts[1]);
		}

		return [$constant, $translation];
	}

	private function identifyRowType(): RowType
	{
		if (strlen(trim($this->original)) === 0)
		{
			return RowType::EMPTY;
		}
		if (str_starts_with(trim($this->original), ';'))
		{
			return RowType::COMMENT;
		}

		return RowType::TRANSLATION;
	}


}