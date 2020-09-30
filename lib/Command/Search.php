<?php

declare(strict_types=1);

namespace OCA\WikipediaSearch\Command;

use OCA\WikipediaSearch\Service\SearchService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Search extends Command {

	/**
	 * @var SearchService
	 */
	private $searchService;

	public function __construct(SearchService $searchService) {
		parent::__construct();

		$this->searchService = $searchService;
	}

	protected function configure(): void {
		$this->setName('wikipedia:search');
		$this->setDescription('Search for wikipedia articles');

		$this->addArgument("term", InputArgument::REQUIRED);
		$this->addArgument("offset", InputArgument::OPTIONAL);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {
		$offset = $input->getArgument("offset");
		$searchResult = $this->searchService->search(
			$input->getArgument("term"),
			$offset === null ? null : ((int) $offset)
		);

		$output->writeln("Here are some articles");
		foreach ($searchResult->getArticles() as $article) {
			$output->writeln("* $article");
		}
		if ($searchResult->getOffset()) {
			$output->writeln("The next results start at offset " . $searchResult->getOffset());
		}

		return 0;
	}
}
