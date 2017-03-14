<?php
class GappCache{
	private static $_MEMCACHE_PREFIX_KEY="g_api_v2.0";		//Memcache 存储前缀
	private static function _buildKey($action,$param) {
		return sprintf("%s.%s",self::$_MEMCACHE_PREFIX_KEY.$action,$param);
	}
	public static function getGameListFromCache($number, $order,$os,$gameid,$v,$game_type,$flag,$start,$limit) {
		$param=func_get_args();
		$mc_key=self::_buildKey('game_list',implode(',',$param));
		return $mc_key;
	}
	public static function getActListFromCache($number, $order,$os,$gameid,$v) {
		$param=func_get_args();
		$mc_key=self::_buildKey('tq_act_list',implode(',',$param));
		return $mc_key;
	}
	public static function getBannerListFromCache($number, $order,$os,$gameid,$v) {
		$param=func_get_args();
		$mc_key=self::_buildKey('tq_banner_list',implode(',',$param));
		return $mc_key;
	}
	public static function getGameDetailFromCache($gameid,$os,$v='1.0',$topic_id='') {
		$param=func_get_args();
		$mc_key=self::_buildKey('game_detail',implode(',',$param));
		return $mc_key;
	}
	public static function getGameFocusFromCache($gameid,$uid,$os,$v='1.0') {
		$param=func_get_args();
		$mc_key=self::_buildKey('game_focus',implode(',',$param));
		return $mc_key;
	}
	public static function getGameMediasFromCache($gameid, $number,$os,$v) {
		$param=func_get_args();
		$mc_key=self::_buildKey('game_medias',implode(',',$param));
		return $mc_key;
	}
}