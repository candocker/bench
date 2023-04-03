<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

trait CrawlerTrait
{
	use RecordListTrait;
	use ShowDealTrait;
	use ListDealTrait;
	use SingleDealTrait;
	use CurlTrait;
	use \ModuleBench\Services\Crawler\Books\FiveQianDealTrait;

}
