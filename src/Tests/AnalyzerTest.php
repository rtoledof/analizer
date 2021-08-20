<?php

use PHPUnit\Framework\TestCase;
use Rtoledof\Analyzer\Analyzer;
use Rtoledof\Analyzer\ILogger;
use Rtoledof\Analyzer\Level;
use Rtoledof\Analyzer\Log;

class AnalyzerTest extends TestCase
{
	/**
	 * @var TestLogger $testLogger
	 */
	private static $testLogger;
	/**
	 * @var Analyzer $analyzer
	 */
	private static $analyzer;

	/**
	 * @throws Exception
	 */
	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();
		$testLogger = new TestLogger();
		$levels = [
			Level::LEVEL_ERROR,
			Level::LEVEL_DEBUG,
			Level::LEVEL_CRITICAL,
			Level::LEVEL_WARNING,
			Level::LEVEL_ALERT,
			Level::LEVEL_INFO,
		];
		for($i = 0; $i < 100; $i++) {
			$log = new Log();
			$log->dateTime = new DateTime();
			$log->dateTime = $log->dateTime->sub(new DateInterval(sprintf('P%dD', rand(0,100))));
			$log->level = $levels[rand(0, count($levels)-1)];
			$log->message = "this is a test message";
		}
		$newAnalyser = new Analyzer($testLogger);
		self::assertInstanceOf(Analyzer::class, $newAnalyser);
		self::$testLogger = $testLogger;
		self::$analyzer = $newAnalyser;
	}

	public function testAnalyze()
	{
		self::$analyzer->analyze((new DateTime('2018-03-04')));
		$logs = self::$analyzer->read(new DateTime('2018-03-04'));
		$this->assertEquals([], $logs);
	}

	public function testStore()
	{
		$log = new Log();
		$log->dateTime = new DateTime();
		$log->level = Level::LEVEL_CRITICAL;
		$log->message = "this is a test message";
		self::$analyzer->store($log);
		$logs = self::$analyzer->read($log->dateTime);
		$this->assertCount(1, $logs);
		$this->assertEquals($log->marshal(), $logs[0]->marshal());
	}

	public function testRead()
	{
		$logs = self::$analyzer->read(new DateTime('2019-03-04'));
		foreach ($logs as $key => $log) {
			$this->assertEquals(self::$testLogger->readTestCases['2019-03-04'][$key], $log->marshal());
		}
	}
}

class TestLogger implements ILogger
{
	public $testCases = [];

	public $readTestCases = [
		'2019-03-04' => [
			'2019-03-04 00:00:05 level: error message: this is a test message\n',
			'2019-03-04 00:01:05 level: debug message: this is a test message\n',
			'2019-03-04 00:08:05 level: alert message: this is a test message\n',
			'2019-03-04 00:10:05 level: info message: this is a test message\n',
			'2019-03-04 00:15:05 level: warning message: this is a test message\n'
		],
		'2018-03-04' => [
			'2018-03-04 00:00:05 level: error message: this is a test message\n',
			'2018-03-04 00:01:05 level: debug message: this is a test message\n',
			'2018-03-04 00:08:05 level: alert message: this is a test message\n',
			'2018-03-04 00:10:05 level: info message: this is a test message\n',
			'2018-03-04 00:15:05 level: warning message: this is a test message\n'
		]
	];

	public function read(string $date): array
	{
		if (array_key_exists($date, $this->readTestCases)) {
			return  $this->readTestCases[$date];
		}
		return [];
	}

	public function delete(string $startDate): void
	{
		if (array_key_exists($startDate, $this->readTestCases)) {
			unset($this->readTestCases[$startDate]);
		}
	}

	public function store(string $log): void
	{
		$exp = '/(?<date>.+\s)level:(?<level>.+)\smessage:(?<message>.+)\\\n/';
		$matches = null;
		preg_match($exp, $log, $matches);
		if ($matches) {
			list($date, $_) = explode(" ", $matches['date']);
			$this->readTestCases[$date][] = $log;
		}
	}
}