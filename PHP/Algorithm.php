<?php

/**
 * 排行榜算法
 * @param $list
 */
function rankSort( $list )
{
	rsort( $list );

	$rtnList = [];
	//上一项目的排序依据值
	$prev = -1;
	//上一排序中项目的数量
	$prevCount = 0;
	//排序值
	$rank = 0;

	foreach( $list as $item )
	{
		if( $item['count'] !== $prev )
		{
			if( !empty($prevCount) )
			{
				$rank += $prevCount;
				$prevCount = 0;
			}
			$item['rank'] = ++$rank;
			$prev = $item['count'];
		}
		else
		{
			$prevCount++;
			$item['rank'] = $rank;
		}
		$rtnList[$item['id']] = $item;
	}
}


/**
 * 数字转中文算法
 * @param $num
 * @param bool $format
 * @param bool $forPrice
 * @return string
 */

function Number2Chinese( $num, $format = true, $forPrice = false )
{
	if( $forPrice )
	{
		$chars = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];
		$units = ['', '拾', '佰', '仟', '', '萬', '億', '兆'];
		$dot = '點';
	}
	else
	{
		$chars = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'];
		$units = ['', '十', '百', '千', '', '万', '亿', '兆'];
		$dot = '点';
	}

	preg_match_all( $format ? '/^0*(\d*)\.?(\d*)/' : '/(\d*)\.?(\d*)/', $num, $match );
	list(, $intArr, $floatArr) = $match;

	$rtnString = '';
	if( ($float = reset( $floatArr )) !== '' )
	{
		$rtnString = $dot . Number2Chinese( $float, false );
	}

	$keep = [];
	if( ($int = reset( $intArr )) !== '' )
	{
		$str = strrev( $int );
		foreach( str_split( $str, 1 ) as $index => $char )
		{
			$keep[$index] = $chars[$char];
			if( $format )
			{
				$keep[$index] .= $str[$index] != '0' ? $units[$index % 4] : '';
				if( $str[$index] + $str[$index - 1] == 0 )
				{
					$keep[$index] = '';
				}
				if( $index % 4 == 0 )
				{
					$keep[$index] .= $units[4 + floor( $index / 4 )];
				}
			}
		}
		$rtnString = join( '', array_reverse( $keep ) ) . $rtnString;
	}
	return $rtnString;
}