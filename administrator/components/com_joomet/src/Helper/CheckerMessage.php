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


// Enum Declaration
enum JoometMessageSource: string
{
	case CONSTANT = 'CONSTANT';     // Errors related to the language constant
	case CONTENT = 'CONTENT';           // Errors related to the translation string comment
	case FORMAT = 'FORMAT';         // Row Format Errors
}

enum JoometErrorType: string
{
	case CONSTANT_MUST_BE_UPPERCASE = 'CONSTANT_MUST_BE_UPPERCASE';
	case CONSTANT_CONTAINS_INVALID_CHAR = 'CONSTANT_CONTAINS_INVALID_CHAR';
	case CONSTANT_ALREADY_DEFINED = 'CONSTANT_ALREADY_DEFINED';
	case TRANSLATION_NOT_ENCAPSULATED = 'TRANSLATION_NOT_ENCAPSULATED';
	case TRANSLATION_LAST_CHAR_IS_BACKSLASH = 'TRANSLATION_LAST_CHAR_IS_BACKSLASH';
	case TRANSLATION_DOUBLE_QUOTES_NOT_ESCAPED = 'TRANSLATION_DOUBLE_QUOTES_NOT_ESCAPED';
	case TRANSLATION_CONTAINS_HTML = 'TRANSLATION_CONTAINS_HTML';
	case TRANSLATION_UNBALANCED_TAGS = 'TRANSLATION_UNBALANCED_TAGS';
}

enum JoometMessageType: string
{
	case ERROR = 'ERROR';
	case WARNING = 'WARNING';
	case INFO = 'INFO';
}

class CheckerMessage
{
	public string $message;
	public JoometMessageSource $source;
	public JoometErrorType $type_id;
	public JoometMessageType $message_type;

	public string $link;

	public function __construct(string $message, JoometMessageSource $source, JoometErrorType $type_id, JoometMessageType $message_type)
	{
		$this->message      = $message;
		$this->source       = $source;
		$this->type_id      = $type_id;
		$this->message_type = $message_type;
		$this->link         = $this->setLinkBasedOnErrorType();
	}

	private function setLinkBasedOnErrorType(): string
	{
		$link = "";
		switch ($this->type_id)
		{
			case JoometErrorType::CONSTANT_MUST_BE_UPPERCASE:
			case JoometErrorType::CONSTANT_CONTAINS_INVALID_CHAR:
			case JoometErrorType::CONSTANT_ALREADY_DEFINED:
				$link = "https://manual.joomla.org/docs/next/general-concepts/multilingual/language-files/#conventions--specifications-for-language-keys-constants";
				break;
			case JoometErrorType::TRANSLATION_NOT_ENCAPSULATED:
			case JoometErrorType::TRANSLATION_DOUBLE_QUOTES_NOT_ESCAPED:
				$link = "https://manual.joomla.org/docs/next/general-concepts/multilingual/language-files/#conventions--specifications-for-language-values";
				break;
			case JoometErrorType::TRANSLATION_CONTAINS_HTML:
				$link = "https://manual.joomla.org/docs/next/general-concepts/multilingual/language-files/#html-in-language-values";
				break;
			case JoometErrorType::TRANSLATION_LAST_CHAR_IS_BACKSLASH:
			case JoometErrorType::TRANSLATION_UNBALANCED_TAGS:
			default:
				break;
		}

		return $link;
	}

}