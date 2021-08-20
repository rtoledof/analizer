<?php

namespace Rtoledof\Analyzer;

class Log {
	const LOG_REGEX = '/(?<date>.+\s)level:(?<level>.+)\smessage:(?<message>.+)\\\n/';
	/**
	 * @var \DateTime $dateTime
	 */
	public $dateTime;
	/**
	 * @var Level $level
	 */
	public $level;
	/**
	 * @var string $message
	 */
	public $message;

	/**
	 * marshal return and string that contain the data in the log with the following format
	 *
	 * Y-m-d H:i:s level: log_level message: log message\n
	 *
	 * @return string
	 */
	public function marshal(): string {
		return sprintf('%s level: %s message: %s\n',
			$this->dateTime->format('Y-m-d H:i:s'),
			$this->level,
			$this->message);
	}

	/**
	 * unmarshal will receive an string that represent the log line that was stored and will convert it to the Log entity
	 *
	 * @param  string  $data
	 * @return Log
	 * @throws \Exception
	 */
	public static function unmarshal(string $data): Log{
		$instance = new self;
		$matches  = null;
		preg_match(self::LOG_REGEX, $data, $matches);
		if ($matches != null) {
			if (array_key_exists('date', $matches)) {
				$instance->dateTime = new \DateTime($matches['date']);
			}
			if (array_key_exists('message', $matches)) {
				$instance->message = trim($matches['message']);
			}
			if (array_key_exists('level', $matches)) {
				$instance->level = Level::unmarshal($matches['level']);
			}
		}
		return $instance;
	}
}