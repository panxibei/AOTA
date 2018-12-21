<?php
/**
 * 这里是公共common模块函数(PHP)调用库(2014/06/21)
 * 
 * 
 */

/**添加时间：201812211321（网上摘录）
 * 将字符串(中文同样实用)转为ascii
 * 注意：我默认当前我们的php文件环境是UTF-8，
 * 如果是GBK的话mb_convert_encoding操作就不需要
 * @param string $namespace 命名空间
 * 示例：
 * 
 */
function strtoascii ($str) {
	$str=mb_convert_encoding($str,'GB2312');
	$change_after='';
	for($i=0;$i<strlen($str);$i++){
		$temp_str=dechex(ord($str[$i]));
		$change_after.=$temp_str[1].$temp_str[0];
	}
	return strtoupper($change_after);
}

	
/**添加时间：201812211321（网上摘录）
 * 将ascii转为字符串(中文同样实用)
 * 注意：我默认当前我们的php文件环境是UTF-8，
 * 如果是GBK的话mb_convert_encoding操作就不需要
 * @param string $namespace 命名空间
 * 示例：
 * 
 */
function asciitostr ($sacii) {
	$asc_arr= str_split(strtolower($sacii),2);
	$str='';
	for($i=0;$i<count($asc_arr);$i++){
		$str.=chr(hexdec($asc_arr[$i][1].$asc_arr[$i][0]));
	}
	return mb_convert_encoding($str,'UTF-8','GB2312');
}


/**添加时间：201707171117（网上摘录）
 * 生成GUID
 * @param string $namespace 命名空间
 * 示例：
 * {E2DFFFB3-571E-6CFC-4B5C-9FEDAAF2EFD7}
 *
 */
function create_guid($namespace = '') {  
	static $guid = '';
	$uid = uniqid("", true);
	$data = $namespace;
	$data .= $_SERVER['REQUEST_TIME'];
	$data .= $_SERVER['HTTP_USER_AGENT'];
	$data .= $_SERVER['SERVER_ADDR'];
	$data .= $_SERVER['SERVER_PORT'];
	$data .= $_SERVER['REMOTE_ADDR'];
	$data .= $_SERVER['REMOTE_PORT'];
	$hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	$guid = '{' . 
		substr($hash, 0, 8) .
		'-' .
		substr($hash, 8, 4) .
		'-' .
		substr($hash, 12, 4) .
		'-' .
		substr($hash, 16, 4) .
		'-' .
		substr($hash, 20, 12) .
		'}';
	return $guid;
}