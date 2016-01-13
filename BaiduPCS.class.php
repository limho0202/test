<?php

/**
 * @desc PCS文件数据接口SDK, 要求PHP运行环境为5.2.0及以上
 * @package  baidu.pcs
 * @author   duanzhenxing(duanzhenxing@baidu.com)
 * @version  2.1.0
 */
require_once dirname ( __FILE__ ) . '/' . 'RequestCore.class.php';

/**
 * @desc BaiduPCS类
 */
class BaiduPCS {

	/**
	 * 百度PCS RESTFUL API SERVER调用地址前缀
	 * @var array
	 */
	private $_pcs_uri_prefixs = array ('https' => 'https://pcs.baidu.com/rest/2.0/pcs/' );

	private $_accessToken = '';

	/**
	 * 初始化accessToken
	 * @param string $accessToken
	 */
	public function __construct($accessToken) {
		$this->_accessToken = $accessToken;
	}

	/**
	 * 设置accessToken
	 * @param string $_accessToken
	 * @return BaiduPCS
	 */
	public function setAccessToken($accessToken) {
		$this->_accessToken = $accessToken;
		return $this;
	}

	/**
	 * 获取accessToken
	 * @return string
	 */
	public function getAccessToken() {
		return $this->_accessToken;
	}

	/**
	 * 调用API
	 * @param string $apiMethod api方法名
	 * @param array || string  $params 请求参数
	 * @param string $method HTTP请求类型
	 * @param string $headers 附加的HTTP HEADER信息
	 * @return string
	 */
	private function _baseControl($apiMethod, $params, $method = 'GET', $headers = array()) {

		$method = strtoupper ( $method );

		if (is_array ( $params )) {
			$params = http_build_query ( $params, '', '&' );
		}

		$url = $this->_pcs_uri_prefixs ['https'] . $apiMethod . ($method == 'GET' ? '&' . $params : '');
		echo $url;
		exit;
		$requestCore = new RequestCore ();
		$requestCore->set_request_url ( $url );

		$requestCore->set_method ( $method );
		if ($method == 'POST') {
			$requestCore->set_body ( $params );
		}

		foreach ( $headers as $key => $value ) {
			$requestCore->add_header ( $key, $value );
		}

		$requestCore->send_request ();
		$result = $requestCore->get_response_body ();

		return $result;
	}

	

	/**
	 * 为当前用户进行视频转码并实现在线实时观看
	 * @param string $path 格式必须为m3u8,m3u,asf,avi,flv,gif,mkv,mov,mp4,m4a,3gp,3g2,mj2,mpeg,ts,rm,rmvb,webm
	 * @param string $type M3U8_320_240、M3U8_480_224、M3U8_480_360、M3U8_640_480和M3U8_854_480
	 * @return 文件播放列表URL
	 */
	public function streaming($path, $type) {
		$result = $this->_baseControl ( 'file?method=streaming' . '&access_token=' . $this->_accessToken, array ('path' => $path, 'type' => $type ) );
		return $result;
	}

}
?>