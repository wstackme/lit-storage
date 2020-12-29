<?php
/*
	GNU GENERAL PUBLIC LICENSE
	Version 2, June 1991

	Hanc marginis exiguitas non caperet.
	https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
*/

if(!defined('__XE__'))
{
	exit();
}

$cache_medal = AddonFunction::getCache('plugins/next_medal');
if(!$cache_medal)
{
	$medal_config = getModel('experience')->getConfig();

	$args = new stdClass();
	$args->regdate = date('Ym');
	$args->exception_member = $medal_config->exception_member;
	$args->list_count = $medal_config->medal_bronze;
	$next_ranks = executeQuery('experience.getMonthRank', $args);

	$cache_medal = [];
	foreach($next_ranks->data as $key => $value)
	{
		array_push($cache_medal, $value->member_srl);
	}

	AddonFunction::setCache('plugins/next_medal', $cache_medal, 3600);
}

if(in_array($member_srl, $cache_medal))
{
	$medal_config = getModel('experience')->getConfig();

	$diamond_cutline = $medal_config->medal_diamond - 1;
	$platinum_cutline = $medal_config->medal_platinum - 1;
	$gold_cutline = $medal_config->medal_gold - 1;
	$silver_cutline = $medal_config->medal_silver - 1;
	$bronze_cutline = $medal_config->medal_bronze - 1;

	foreach($cache_medal as $key => $value)
	{
		if($value == $member_srl)
		{
			if($key <= $diamond_cutline)
			{
				return '다이아몬드 메달';
			}
			else if($key <= $platinum_cutline)
			{
				return '백금메달';
			}
			else if($key <= $gold_cutline)
			{
				return '금메달';
			}
			else if($key <= $silver_cutline)
			{
				return '은메달';
			}
			else
			{
				return '동메달';
			}
		}
	}
}

return '순위권 외';