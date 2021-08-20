<?php

use Rtoledof\Analyzer\Log;
use Rtoledof\Analyzer\Level;
use PHPUnit\Framework\TestCase;

class LogTestCase {
	/**
	 * @var string $entry
	 */
	public $entry;
	/**
	 * @var Log $log
	 */
	public $log;
}

class LogTest extends TestCase {
	/**
	 * @var LogTestCase[]
	 */
	private static $testCases = [];

	public static function setUpBeforeClass(): void{
		parent::setUpBeforeClass();
		$levels = [
			Level::LEVEL_ERROR,
			Level::LEVEL_DEBUG,
			Level::LEVEL_CRITICAL,
			Level::LEVEL_WARNING,
			Level::LEVEL_ALERT,
			Level::LEVEL_INFO,
		];
		for ($i = 0; $i < 100; $i++) {
			$tc        = new LogTestCase();
			$level = $levels[rand(0, count($levels) - 1)];
			$date = date('Y-m-d H:i:s');
			$message = 'this is a test case message';
			$tc->entry = sprintf('%s level: %s message: %s\n',
				$date,
				$level,
				$message
			);
			$log = new Log();
			$log->level = $level;
			try {
				$log->dateTime = new DateTime($date);
			} catch (Exception $e) {
				$log->dateTime = null;
			}
			$log->message = $message;
			$tc->log = $log;

			self::$testCases[] = $tc;
		}
	}

	public function testUnmarshal() {
		foreach (self::$testCases as $testCase) {
			$log = Log::unmarshal($testCase->entry);
			$this->assertEquals($testCase->log->level, $log->level);
			$this->assertEquals($testCase->log->message, $log->message);
			$this->assertEquals($testCase->log->dateTime->format('Y-m-d H:i:s'), $log->dateTime->format('Y-m-d H:i:s'));
		}
	}

	public function testMarshal() {
		foreach (self::$testCases as $testCase) {
			$this->assertEquals($testCase->entry, $testCase->log->marshal());
		}
	}
}
