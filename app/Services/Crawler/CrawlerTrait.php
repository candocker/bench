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

	//use \bench\spider\models\crawler\maigoo\MaigooDealTrait;
	//use \bench\spider\models\crawler\maigoo\MaigooInfoTrait;
	//use \bench\spider\models\crawler\maigoo\MaigooRecordTrait;
}
