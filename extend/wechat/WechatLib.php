<?php
/**
 * 微信公众平台， 认证中心，消息分发
 *
 */
namespace wechat;
class WechatLib
{
	/**
	 * 加密请求
	 */
	public static function encryptMsg($appId, $token, $aesKey, $msg)
	{	
		if (! $msg)
		{
			return "";
		}
	
		if (isset($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes')
		{
			include_once 'aes/wxBizMsgCrypt.php';
			
			$reqTimeStamp 	= isset($_GET['timestamp']) ? intval($_GET['timestamp']) : 0;
			$reqNonce 		= isset($_GET['nonce']) ? trim($_GET['nonce']) : '';
			$encryptMsg 	= isset($_GET['msg_signature']) ? trim($_GET['msg_signature']) : '';
				
			$wxcptObj 		= new WXBizMsgCrypt($token, $aesKey, $appId);
			$encryptMsg 	= ""; //xml格式密文
			$errCode 		= $wxcptObj->encryptMsg($msg, $reqTimeStamp, $reqNonce, $encryptMsg);
			if ($errCode == 0)
			{
				$msg = $encryptMsg;
			}
			else
			{
				return "";
			}
		}
	
		return $msg;
	}
	
	/**
	 * 解析请求
	 * @param unknown $appId
	 * @param unknown $token
	 * @param unknown $aesKey
	 * @return array
	 */
	public static function decryptRequest($appId, $token, $aesKey)
	{
		$content = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? trim($GLOBALS["HTTP_RAW_POST_DATA"]) : '2';
		if (isset($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes')
		{
			include_once 'aes/wxBizMsgCrypt.php';
			$reqTimeStamp = isset($_GET['timestamp']) ? intval($_GET['timestamp']) : 0;
			$reqNonce     = isset($_GET['nonce']) ? trim($_GET['nonce']) : '';
			$encryptMsg   = isset($_GET['msg_signature']) ? trim($_GET['msg_signature']) : '';
	
			$wxcptObj 	= new WXBizMsgCrypt($token, $aesKey, $appId);
	
			$sMsg 		= "";  //解析后的明文
			$errCode 	= $wxcptObj->decryptMsg($encryptMsg, $reqTimeStamp, $reqNonce, $content, $sMsg);
			if ($errCode != 0)
			{
				exit();
			}
			else
			{
				$content = $sMsg;
			}
		}

		$xml = (array)simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);

		return array_change_key_case($xml, CASE_LOWER);
	}

	/**
	 * 判断验证请求的签名信息是否正确
	 * @param string $token
	 * @return boolean
	 */
	public static function validateSignature($token)
	{
		$signature = $_GET['signature'];
		$timestamp = $_GET['timestamp'];
		$nonce	   = $_GET['nonce'];
		
		$signatureAry = array($token, $timestamp, $nonce);
		sort($signatureAry, SORT_STRING);
		return sha1(implode($signatureAry)) == $signature;
	}


}