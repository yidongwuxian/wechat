<?php
/**
 * 微信消息回复
 */
namespace wechat;

class ResponseLib
{
	/**
	 * 回复文本消息
	 * @param unknown $fromUsername
	 * @param unknown $toUsername
	 * @param unknown $content
	 * @param number $funcFlag
	 * @return string
	 */
	public static function text($fromUsername, $toUsername, $content, $funcFlag=0)
	{
		$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>%s</FuncFlag>
					 </xml>";
		return sprintf($template, $fromUsername, $toUsername, time(), $content, $funcFlag);
	}
	
	/**
	 * 回复图片消息
	 * @param unknown $fromUsername
	 * @param unknown $toUsername
	 * @param unknown $mediaId
	 * @param number $funcFlag
	 * @return string
	 */
	public static function image($fromUsername, $toUsername, $mediaId, $funcFlag = 0)
	{
		$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[image]]></MsgType>
						<Image>
						<MediaId><![CDATA[%s]]></MediaId>
						</Image>
						<FuncFlag>%s</FuncFlag>
					 </xml>";
		return sprintf($template, $fromUsername, $toUsername, time(), $mediaId, $funcFlag);
	}
	
	/**
	 * 回复音频消息
	 * @param unknown $fromUsername
	 * @param unknown $toUsername
	 * @param unknown $mediaId
	 * @param number $funcFlag
	 * @return string
	 */
	public static function voice($fromUsername, $toUsername, $mediaId, $funcFlag=0)
	{
		$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[voice]]></MsgType>
						<Voice>
						<MediaId><![CDATA[%s]]></MediaId>
						</Voice>
						<FuncFlag>%s</FuncFlag>
					 </xml>";
		return sprintf($template, $fromUsername, $toUsername, time(), $mediaId, $funcFlag);
	}
	
	/**
	 * 回复视频消息
	 * @param unknown $fromUsername
	 * @param unknown $toUsername
	 * @param unknown $mediaId
	 * @param number $funcFlag
	 * @return string
	 */
	public static function video($fromUsername, $toUsername, $mediaId, $title = '', $desc = '', $funcFlag=0)
	{
		$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[video]]></MsgType>
						<Video>
						<MediaId><![CDATA[%s]]></MediaId>
						<Title><![CDATA[%s]]></Title>
						<Description><![CDATA[%s]]></Description>
						</Video>
						<FuncFlag>%s</FuncFlag>
					 </xml>";
		return sprintf($template, $fromUsername, $toUsername, time(), $mediaId, $title, $desc, $funcFlag);
	}
	
	/**
	 * 回复音乐消息
	 * @param unknown $fromUsername
	 * @param unknown $toUsername
	 * @param unknown $title
	 * @param unknown $desc
	 * @param unknown $musicUrl
	 * @param unknown $hqMusicUrl
	 * @param unknown $thumbMediaId
	 * @param number $funcFlag
	 * @return string
	 */
	public static function music($fromUsername, $toUsername, $title, $desc, $musicUrl, $hqMusicUrl, $thumbMediaId, $funcFlag=0)
	{
		$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>12345678</CreateTime>
						<MsgType><![CDATA[music]]></MsgType>
						<Music>
						<Title><![CDATA[%s]]></Title>
						<Description><![CDATA[%s]]></Description>
						<MusicUrl><![CDATA[%s]]></MusicUrl>
						<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
						<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
						</Music>
						<FuncFlag>%s</FuncFlag>
					 </xml>";
		return sprintf($template, $fromUsername, $toUsername, $title, $desc, $musicUrl, $hqMusicUrl, $thumbMediaId, $funcFlag);
	}
	
	/**
	 * 回复图文消息
	 * @param unknown $fromUsername
	 * @param unknown $toUsername
	 * @param unknown $articles
	 * @param number $funcFlag
	 * @return string
	 */
	public static function news($fromUsername, $toUsername, $articles=array(), $funcFlag=0)
	{
		if (count($articles) > 10)
		{
			return "图文消息项目不能超过10条";
		}
		
		$itemTemplate = "<item>
							<Title><![CDATA[%s]]></Title> 
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
						</item>";
		$itemStr = "";
		foreach ($articles as $article)
		{
			$itemStr .= sprintf($itemTemplate, $article['title'], $article['description'], $article['picurl'], $article['url']);
		}
		
		$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[news]]></MsgType>
						<ArticleCount>%s</ArticleCount>
						<Articles>%s</Articles>
					 </xml> ";

		return sprintf($template, $fromUsername, $toUsername, time(), count($articles), $itemStr);
	}
}