<?php

class WxMsgProtocol
{
	public static $_REPLY_MSG_TYPE_NEWS=
	"<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<ArticleCount>%s</ArticleCount>
		<Articles>
			%s
		</Articles>
	</xml>";
	
	public static $_REPLY_MSG_TYPE_NEWS_SUBITEM=
	"<item>
		<Title><![CDATA[%s]]></Title>
		<Description><![CDATA[%s]]></Description>
		<PicUrl><![CDATA[%s]]></PicUrl>
		<Url><![CDATA[%s]]></Url>
	</item>";
	
	public static $_REPLY_MSG_TYPE_TEXT=
	"<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[text]]></MsgType>
		<Content><![CDATA[%s]]></Content>
	</xml>";
}
