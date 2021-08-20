<?php

namespace Rtoledof\Analyzer;

class Level {
	const LEVEL_INFO     = 'info';
	const LEVEL_ALERT    = 'alert';
	const LEVEL_WARNING  = 'warning';
	const LEVEL_CRITICAL = 'critical';
	const LEVEL_DEBUG    = 'debug';
	const LEVEL_ERROR    = 'error';

	/**
	 * unmarshal receive a string level and return the
	 *
	 * @param  string  $level
	 * @return string
	 * @throws \Exception
	 */
	public static function unmarshal(string $level): string
	{
		switch (trim($level)) {
			case self::LEVEL_INFO:
			case self::LEVEL_ALERT:
			case self::LEVEL_WARNING:
			case self::LEVEL_CRITICAL:
			case self::LEVEL_DEBUG:
				return trim($level);
			default:
				return self::LEVEL_ERROR;
		}
	}
}