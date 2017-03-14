<?php

LunaLoader::import('luna_lib.hps.HpsClient');

/**
 *	HPS Base Model Model基类
 *
 *	@author xulong <xulong@snda.com>
 *
 *	HPS访问的数据模型基类，实现了以属性方式存取HPS请求参数，并对
 *  参数提供了约束检查：包括参数值的非空（""）的检查，值作为常量
 *  和可选值的定义。
 */
abstract class HpsModel {
	
	/**
	 * 数组中关键字定义常量,缺省值。
	 * @var 关键字常量。
	 */
	const PARAM_KEY_DEFAULTVALUE="value";
	/**
	 * 数组中关键字定义常量,值。
	 * @var 关键字常量。
	 */
	const PARAM_KEY_VALUE="value";
	/**
	 * 数组中关键字定义常量,常量值。
	 * @var 关键字常量。
	 */	
	const PARAM_KEY_CONST="const";
	/**
	 * 数组中关键字定义常量,必填值。
	 * @var 关键字常量。
	 */
	const PARAM_KEY_REQUIRED="required";
	/**
	 * 数组中关键字定义常量,可选值范围。
	 * @var 关键字常量。
	 */
	const PARAM_KEY_ENUM="enum";

	/**
	 * HPS除去domain外的URL路径信息 
	 * @var string 除去domain外的URL路径信息
	 */
	protected	$_URL;
	/**
	 * 请求参数的约束和值定义存储。
	 * @var array 保存请求参数的约束和值定义。
	 */
	protected	$_PARAM;
	/**
	 * 参数存取启用schema检查标志。
	 * @var bool 参数存取是否启用schema检查。
	 */
	protected 	$_CHECK_SCHEMA;
	
	protected $_modelClassName;
	
	
	/**
	 * 实例化HPS访问的数据模型。
	 * @param string $url 访问的URL Path路径信息。path 路径以/开头。
	 * @param array $param 接口请求参数的约束Schema定义。
	 * @param bool $checkSchema 指示是否启用Schema检查请求参数的存取
	 * @param string $className 子类实例的类名称。
	 */
	public static function model($url,$param=array(),$checkSchema=false,$className=__CLASS__)
	{
		$model=new $className(null);
		$model->_URL=$url;
		$model->_PARAM=$param;
		$model->_CHECK_SCHEMA=$checkSchema;
		$model->_modelClassName=$className;
		return $model; 
	}
	
	/**
	 * Magic method。实现请求参数以属性方式读。
	 * @param string $name 属性名称
	 * @throws Exception
	 */
	public function __get($name)
	{
		if($this->_CHECK_SCHEMA){
			if(isset($this->_PARAM[$name])==false){
				throw new Exception("Property ".get_class($this).".$name is not defined.");
			}			
			if(array_key_exists(HpsModel::PARAM_KEY_VALUE,$this->_PARAM[$name])){
				return $this->_PARAM[$name][HpsModel::PARAM_KEY_VALUE];
			} 
		}
		if(isset($this->_PARAM[$name]) && array_key_exists(HpsModel::PARAM_KEY_VALUE,$this->_PARAM[$name])){
			return $this->_PARAM[$name][HpsModel::PARAM_KEY_VALUE];
		}
		return "";
	}
	/**
	 * Magic method。实现请求参数以属性方式写值。
	 * @param string $name 属性名称
	 * @param $value 属性值 		
	 * @throws Exception
	 */
	public function __set($name,$value)
	{
		if($this->_CHECK_SCHEMA){
			if(isset($this->_PARAM[$name])==false){
				throw new Exception("Property ".get_class($this).".$name is not defined.");
			}
			if(isset($this->_PARAM[$name][HpsModel::PARAM_KEY_CONST]) && $this->_PARAM[$name][HpsModel::PARAM_KEY_CONST]==true){
				throw new Exception("Property ".get_class($this).".$name is const property.");
			}
		}
		if(isset($this->_PARAM[$name])==false){
			$this->_PARAM[$name]=array(HpsModel::PARAM_KEY_VALUE => $value);
		}
		else{
			$this->_PARAM[$name][HpsModel::PARAM_KEY_VALUE]=$value;
		}
	}
	/**
	 * 检查请求参数值是否正确
	 * @param string $name 请求参数名称
	 * @param $value 请求参数值
	 * @param string $refMsg 引用方式，存储参数检查出错时的提示信息
	 */
	private function checkParam($name,$value,&$refMsg)
	{
		$refMsg="";
		if($this->_CHECK_SCHEMA){
			if(isset($this->_PARAM[$name][HpsModel::PARAM_KEY_REQUIRED]) && ($value=="" && is_numeric($value)==false)){
				$refMsg="Property ".get_class($this).".$name is required,please assign value.";
				return false;
			}
			if(isset($this->_PARAM[$name][HpsModel::PARAM_KEY_ENUM])){
				foreach ($this->_PARAM[$name][HpsModel::PARAM_KEY_ENUM] as $enumValue){
					if($value==$enumValue){
						return true;
					}
				}
				$refMsg="Property ".get_class($this).".$name is enum type,$value is not valid.";
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 调用HpsClient，访问HPS的接口。
	 * @return array 将通过HpsClient的访问结果作为返回值。
	 * @throws Exception
	 */
	public function submit()
	{
		$hps = new HpsClient($this->_modelClassName);
		$params = array();
		foreach($this->_PARAM as $name => $field){
			$value="";
			if(array_key_exists(HpsModel::PARAM_KEY_VALUE,$field)){
				$value=$field[HpsModel::PARAM_KEY_VALUE];
			}
			if($this->checkParam($name, $value, $refMsg)==false){
				throw new Exception($refMsg);
			}
			$params[$name]=$value;
		}
		$resp = $hps->getData($this->_URL, $params);
		$data = @json_decode($resp, true);
		return $data;
	}
}

?>