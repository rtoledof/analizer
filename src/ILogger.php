<?php

namespace Rtoledof\Analyzer;

interface ILogger {
	/**
	 * read will read the last 100 logs from the specific resource.
	 *
	 * @param  string  $date
	 * @return string[]
	 */
	public function read(string $date): array;

	/**
	 * delete remove the entry from the logs from older that the given period.
	 * If a source is passed then it will remove the logs only from the given source.
	 *
	 * @param  string  $startDate
	 * @return void
	 */
	public function delete(string $startDate): void;

	/**
	 * store will add a new entry on the logger.
	 * If the source is specified the entry will only be stored in the given source otherwise
	 * it will be stored in all the stores.
	 *
	 * @param  string  $log
	 * @return void
	 */
	public function store(string $log): void;
}
