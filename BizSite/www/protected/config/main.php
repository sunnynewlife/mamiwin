<?php

define('BASE_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'..');
Yii::setPathOfAlias('lib', BASE_PATH . DIRECTORY_SEPARATOR . 'libraries');
Yii::setPathOfAlias('model', BASE_PATH . DIRECTORY_SEPARATOR . 'models');
Yii::setPathOfAlias('config', BASE_PATH . DIRECTORY_SEPARATOR . 'config');
Yii::setPathOfAlias('widget', BASE_PATH . DIRECTORY_SEPARATOR . 'widget');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Homework Site',
	'timeZone'=>'Asia/Shanghai',

	'import'=>array(
		'application.components.*',
		'application.models.*',
		'application.libraries.*',
		'application.modules.*',
	),

    'defaultController'=>'site/index',
    'layout' => 'main',

	'onBeginRequest' => array('YIILogerPersist', 'HttpRequestBegin'),
	'onEndRequest' => array('YIILogerPersist','HttpRequestEnd'),
		
	'components'=>array(
		'session'=>array(
				'class' => 'LunaSession',
				'autoStart' => true,
				'cookieMode' => 'allow',
				'timeout' => '1800', // session生存期30分钟
		 ),
		 'urlManager'=>array(
			 'urlFormat'=>'path',
			 'rules'=>array(
					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			  ),
		 	'showScriptName'=> false
		  ),
	),
	'params'=>array(
	)
);
