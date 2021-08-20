<?php

namespace Rtoledof\Analyzer;

use DateTime;

class Analyzer {
	/**
	 * @var ILogger $logger
	 */
	private $logger;

	/**
	 * @param  ILogger  $logger
	 */
	public function __construct(ILogger $logger) {
		$this->logger = $logger;
	}

	/**
	 * read the logs from the logger and return a list of the logs
	 *
	 * @param  DateTime  $dateTime
	 * @return Log[]
	 * @throws \Exception
	 */
	public function read(DateTime $dateTime): array
	{
		$entries  = $this->logger->read($dateTime->format('Y-m-d'));
		$response = [];
		foreach ($entries as $entry) {
			$response[] = Log::unmarshal($entry);
		}
		return $response;
	}

	/**
	 * @param DateTime $dateTime
	 */
	public function analyze(DateTime $dateTime) {
		$this->logger->delete($dateTime->format('Y-m-d'));
	}

	public function store(Log $log)
	{
		$this->logger->store($log->marshal());
	}
}