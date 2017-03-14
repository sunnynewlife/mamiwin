<?php

class LunaAccessLogController extends Controller 
{
	public function actionSiteProfile()
	{
		$this->renderData["SiteName"]="";
		$this->renderData["ServerIp"]="";
		$this->renderData["logLevel"]="";
		$this->renderData["ModuleType"]="";
		$this->renderData["RequestUri"]="";
		$this->renderData["RequestTimeMin"]="";
		$this->renderData["RequestTimeMax"]="";
		
		$this->renderData["Summary_Count"]="";
		$this->renderData["Summary_Avg"]="";
		$this->renderData["Summary_Max"]="";
		$this->renderData["Summary_Min"]="";
		
		$this->renderData["XAxis"]="";
		$this->renderData["YAxis"]="";
		
		$submit = trim(Yii::app()->request->getParam('search',0));
		if($submit){
			$SiteName			=	trim(Yii::app()->request->getParam('SiteName',""));
			$ServerIp			=	trim(Yii::app()->request->getParam('ServerIp',""));
			$logLevel			=	trim(Yii::app()->request->getParam('logLevel',""));
			$ModuleType			=	trim(Yii::app()->request->getParam('ModuleType',""));
			$RequestUri			=	trim(Yii::app()->request->getParam('RequestUri',""));
			$RequestTimeMin		=	trim(Yii::app()->request->getParam('RequestTimeMin',""));
			$RequestTimeMax		=	trim(Yii::app()->request->getParam('RequestTimeMax',""));
			
			$this->renderData["SiteName"]=$SiteName;
			$this->renderData["ServerIp"]=$ServerIp;
			$this->renderData["logLevel"]=$logLevel;
			$this->renderData["ModuleType"]=$ModuleType;
			$this->renderData["RequestUri"]=$RequestUri;
			$this->renderData["RequestTimeMin"]=$RequestTimeMin;
			$this->renderData["RequestTimeMax"]=$RequestTimeMax;
				
			$fAppData=new FAppData();
			
			if($ModuleType=="Uri"){
				$sqlWhere=" where SiteName='$SiteName' ";
				$sqlList="select ExecuteDuration from LunaAccessLogHistory ";
				if(empty($RequestUri)==false){
					$sqlWhere.=" and RequestUri like '%$RequestUri%' ";
				}
				if(empty($ServerIp)==false){
					$sqlWhere.=" and ServerIp like '%$ServerIp%' ";
				}
				if(empty($RequestTimeMin)==false){
					$sqlWhere.=" and RequestTime >='$RequestTimeMin' ";
				}
				if(empty($RequestTimeMax)==false){
					$sqlWhere.=" and RequestTime <='$RequestTimeMax' ";
				}
			
				$sqlSummary="select count(1) as sCount,max(ExecuteDuration) as maxExecuteDuration,
						min(ExecuteDuration) as minExecuteDuration,avg(ExecuteDuration) as avgExecuteDuration
						from LunaAccessLogHistory ".$sqlWhere;
				
				$summaryInfo=$fAppData->queryWithSql($sqlSummary);
				if(count($summaryInfo)>0){
					$this->renderData["Summary_Count"]=$summaryInfo[0]["sCount"];
					$this->renderData["Summary_Avg"]=$summaryInfo[0]["avgExecuteDuration"]." 毫秒";
					$this->renderData["Summary_Max"]=$summaryInfo[0]["maxExecuteDuration"]." 毫秒";
					$this->renderData["Summary_Min"]=$summaryInfo[0]["minExecuteDuration"]." 毫秒";
				}
				$accessLog=$fAppData->queryWithSql($sqlList.$sqlWhere." order by RequestTime asc");
				$xData=array();
				$yData=array();
				$posIndex=0;
				foreach ($accessLog as $aRowItem){
					$xData[]=$posIndex++;
					$yData[]=$aRowItem["ExecuteDuration"];
				}
				$this->renderData["XAxis"]=implode(",",$xData);
				$this->renderData["YAxis"]=implode(",",$yData);
				
			}else {
				$sqlWhere=" where a.SiteName='$SiteName' ";
				
				$sqlList="select a.ModelDuration from  LunaAccessDetailLogHistory a left join LunaAccessLogHistory b on b.RecGUID=a.RecGUID";
				
				$sqlOrderBy=" order by b.RequestTime desc";
				
				$summaryInfo="select count(1) as sCount,max(a.ModelDuration) as maxExecuteDuration,
						min(a.ModelDuration) as minExecuteDuration,avg(a.ModelDuration) as avgExecuteDuration
						from LunaAccessDetailLogHistory a  
						left join LunaAccessLogHistory b on b.RecGUID=a.RecGUID";
				
				$sqlWhere=" where a.SiteName='$SiteName' ";
				$sqlWhere.=" and a.LogModelType = '$ModuleType' ";
				
				if(empty($ServerIp)==false){
					$sqlWhere.=" and b.ServerIp like '%$ServerIp%' ";
				}
				if(empty($logLevel)==false){
					$sqlWhere.=" and a.LevelType = '$logLevel' ";
				}
				if(empty($RequestUri)==false){
					$sqlWhere.=" and a.msg_1 like '%$RequestUri%' ";
				}
				if(empty($RequestTimeMin)==false){
					$sqlWhere.=" and b.RequestTime >='$RequestTimeMin' ";
				}
				if(empty($RequestTimeMax)==false){
					$sqlWhere.=" and b.RequestTime <='$RequestTimeMax' ";
				}
				
				$summaryInfo=$fAppData->queryWithSql($summaryInfo.$sqlWhere);
				if(count($summaryInfo)>0){
					$this->renderData["Summary_Count"]=$summaryInfo[0]["sCount"];
					$this->renderData["Summary_Avg"]=bcmul($summaryInfo[0]["avgExecuteDuration"], "1.0",5)." 秒";
					$this->renderData["Summary_Max"]=$summaryInfo[0]["maxExecuteDuration"]." 秒";
					$this->renderData["Summary_Min"]=$summaryInfo[0]["minExecuteDuration"]." 秒";
				}
				
				$accessLog=$fAppData->queryWithSql($sqlList.$sqlWhere.$sqlOrderBy);
				$xData=array();
				$yData=array();
				$posIndex=0;
				foreach ($accessLog as $aRowItem){
					$xData[]=$posIndex++;
					$yData[]=$aRowItem["ModelDuration"];
				}
				$this->renderData["XAxis"]=implode(",",$xData);
				$this->renderData["YAxis"]=implode(",",$yData);
			}
		}		
		$this->render("graph",$this->renderData);
	}
	
    public function actionIndex()
    {
    	$this->renderData["page"]="";
    	$this->renderData["AccessLogList"]=array();
    	$this->renderData["AccessLogDetailList"]=array();
    	
    	$this->renderData["SiteName"]="";
    	$this->renderData["UserIp"]="";
    	$this->renderData["ServerIp"]="";
    	$this->renderData["SessionId"]="";
    	$this->renderData["RecGUID"]="";
    	$this->renderData["logLevel"]="";
    	$this->renderData["ModuleType"]="";
    	$this->renderData["ExecuteDurationMin"]="";
    	$this->renderData["ExecuteDurationMax"]="";
    	$this->renderData["RequestTimeMin"]="";
    	$this->renderData["RequestTimeMax"]="";
    	$this->renderData["RequestUri"]="";
    	$this->renderData["msg_1"]="";
    	$this->renderData["msg_2"]="";
    	$this->renderData["msg_3"]="";
    	$this->renderData["msg_4"]="";
    	 
    	
    	
    	$submit = trim(Yii::app()->request->getParam('search',0));
    	if($submit){

    		$SiteName			=	trim(Yii::app()->request->getParam('SiteName',""));
    		$UserIp				=	trim(Yii::app()->request->getParam('UserIp',""));
    		$ServerIp			=	trim(Yii::app()->request->getParam('ServerIp',""));
    		$SessionId			=	trim(Yii::app()->request->getParam('SessionId',""));
    		$RecGUID			=	trim(Yii::app()->request->getParam('RecGUID',""));
    		$logLevel			=	trim(Yii::app()->request->getParam('logLevel',""));
    		$ModuleType			=	trim(Yii::app()->request->getParam('ModuleType',""));
    		$ExecuteDurationMin	=	trim(Yii::app()->request->getParam('ExecuteDurationMin',""));
    		$ExecuteDurationMax	=	trim(Yii::app()->request->getParam('ExecuteDurationMax',""));
    		$RequestTimeMin		=	trim(Yii::app()->request->getParam('RequestTimeMin',""));
    		$RequestTimeMax		=	trim(Yii::app()->request->getParam('RequestTimeMax',""));
    		$RequestUri			=	trim(Yii::app()->request->getParam('RequestUri',""));
    		$msg_1				=	trim(Yii::app()->request->getParam('msg_1',""));
    		$msg_2				=	trim(Yii::app()->request->getParam('msg_2',""));
    		$msg_3				=	trim(Yii::app()->request->getParam('msg_3',""));
    		$msg_4				=	trim(Yii::app()->request->getParam('msg_4',""));
    		
    		$this->renderData["SiteName"]=$SiteName;
    		$this->renderData["UserIp"]=$UserIp;
    		$this->renderData["ServerIp"]=$ServerIp;
    		$this->renderData["SessionId"]=$SessionId;
    		$this->renderData["RecGUID"]=$RecGUID;
    		$this->renderData["logLevel"]=$logLevel;
    		$this->renderData["ModuleType"]=$ModuleType;
    		$this->renderData["ExecuteDurationMin"]=$ExecuteDurationMin;
    		$this->renderData["ExecuteDurationMax"]=$ExecuteDurationMax;
    		$this->renderData["RequestTimeMin"]=$RequestTimeMin;
    		$this->renderData["RequestTimeMax"]=$RequestTimeMax;
    		$this->renderData["RequestUri"]=$RequestUri;
    		$this->renderData["msg_1"]=$msg_1;
    		$this->renderData["msg_2"]=$msg_2;
    		$this->renderData["msg_3"]=$msg_3;
    		$this->renderData["msg_4"]=$msg_4;
    		
    		$sqlList="select a.SiteName,a.RecGUID,a.LevelType,a.LogModelType,a.ModelDuration,a.msg_1,a.msg_2,a.msg_3,a.msg_4,
    					b.SessionId,b.ExecuteDuration,b.RequestTime,b.RequestUri,b.RequestMethod,b.RequestParameter,b.UserIp,b.ServerIp   
    				from  LunaAccessDetailLogHistory a
    				left join LunaAccessLogHistory b on b.RecGUID=a.RecGUID";
    		
    		$sqlOrderBy=" order by b.RequestTime desc";
    		
    		$sqlCount="select count(1) as logCount from  LunaAccessDetailLogHistory a  left join LunaAccessLogHistory b on b.RecGUID=a.RecGUID";
    		
    		$sqlWhere=" where a.SiteName='$SiteName' ";
    		
    		if(empty($UserIp)==false){
    			$sqlWhere.=" and b.UserIp like '%$UserIp%' ";
    		}
    		if(empty($ServerIp)==false){
    			$sqlWhere.=" and b.ServerIp like '%$ServerIp%' ";
    		}
    		if(empty($SessionId)==false){
    			$sqlWhere.=" and b.SessionId like '%$SessionId%' ";
    		}
    		if(empty($RecGUID)==false){
    			$sqlWhere.=" and a.RecGUID like '%$RecGUID%' ";
    		}
    		if(empty($logLevel)==false){
    			$sqlWhere.=" and a.LevelType = '$logLevel' ";
    		}
    		if(empty($ModuleType)==false){
    			$sqlWhere.=" and a.LogModelType = '$ModuleType' ";
    		}
    		if(empty($RequestTimeMin)==false){
    			$sqlWhere.=" and b.RequestTime >= '$RequestTimeMin' ";
    		}
    		if(empty($RequestTimeMax)==false){
    			$sqlWhere.=" and b.RequestTime <= '$RequestTimeMax' ";
    		}
    		if(empty($RequestUri)==false){
    			$sqlWhere.=" and b.RequestUri like '%$RequestUri%' ";
    		}
    		if(empty($msg_1)==false){
    			$sqlWhere.=" and a.msg_1 like '%$msg_1%' ";
    		}
    		if(empty($msg_2)==false){
    			$sqlWhere.=" and a.msg_2 like '%$msg_2%' ";
    		}
    		if(empty($msg_3)==false){
    			$sqlWhere.=" and a.msg_3 like '%$msg_3%' ";
    		}
    		if(empty($msg_4)==false){
    			$sqlWhere.=" and a.msg_4 like '%$msg_4%' ";
    		}
    		
    		if(empty($ExecuteDurationMin)==false){
    			$sqlWhere.=" and a.ModelDuration >=$ExecuteDurationMin";
    		}
    		if(empty($ExecuteDurationMax)==false){
    			$sqlWhere.=" and a.ModelDuration <=$ExecuteDurationMax";
    		}
    		
    		$fAppData=new FAppData();
    		
    		$rowCountInfo=$fAppData->queryWithSql($sqlCount.$sqlWhere);
    		if(count($rowCountInfo>0) && $rowCountInfo[0]["logCount"]>0){
    			
    			$row_count=$rowCountInfo[0]["logCount"];
    			$page_size = 50;
    			$page_no  = Yii::app()->request->getParam('page_no',1);
    			
    			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
    			$total_page=$total_page<1?1:$total_page;
    			$page_no=$page_no>($total_page)?($total_page):$page_no;
    			$start = ($page_no - 1) * $page_size;
    			
    			$PageShowUrl="/lunaAccessLog/index?search=1";
    			
    			
    			if(empty($SiteName)==false){
    				$PageShowUrl.="&SiteName=".$SiteName;
    			}
    			if(empty($UserIp)==false){
    				$PageShowUrl.="&UserIp=".$UserIp;
    			}
    			if(empty($ServerIp)==false){
    				$PageShowUrl.="&ServerIp=".$ServerIp;
    			}
    			if(empty($RecGUID)==false){
    				$PageShowUrl.="&RecGUID=".$RecGUID;
    			}
    			if(empty($SessionId)==false){
    				$PageShowUrl.="&SessionId=".$SessionId;
    			}
    			if(empty($logLevel)==false){
    				$PageShowUrl.="&logLevel=".$logLevel;
    			}
    			if(empty($ModuleType)==false){
    				$PageShowUrl.="&ModuleType=".$ModuleType;
    			}
    			if(empty($ExecuteDurationMin)==false){
    				$PageShowUrl.="&ExecuteDurationMin=".$ExecuteDurationMin;
    			}
    			if(empty($ExecuteDurationMax)==false){
    				$PageShowUrl.="&ExecuteDurationMax=".$ExecuteDurationMax;
    			}
    			if(empty($RequestTimeMin)==false){
    				$PageShowUrl.="&RequestTimeMin=".$RequestTimeMin;
    			}
    			if(empty($RequestTimeMax)==false){
    				$PageShowUrl.="&RequestTimeMax=".$RequestTimeMax;
    			}
    			 
    			if(empty($RequestUri)==false){
    				$PageShowUrl.="&RequestUri=".$RequestUri;
    			}
    			if(empty($msg_1)==false){
    				$PageShowUrl.="&RequestUri=".$msg_1;
    			}
    			if(empty($msg_2)==false){
    				$PageShowUrl.="&RequestUri=".$msg_2;
    			}
    			if(empty($msg_3)==false){
    				$PageShowUrl.="&RequestUri=".$msg_3;
    			}
    			if(empty($msg_4)==false){
    				$PageShowUrl.="&RequestUri=".$msg_4;
    			}
    			$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);    			
    			
    			$accessLogList=$fAppData->queryWithSql($sqlList.$sqlWhere.$sqlOrderBy." limit $start,$page_size");
    			$this->renderData["AccessLogDetailList"]=$accessLogList;
    			
    			$UriLogs=array();
				foreach ($accessLogList as $rowItem){
					if(isset($UriLogs[$rowItem["RecGUID"]])==false){
						$UriLogs[$rowItem["RecGUID"]]=$rowItem;
					}
				}
    			$this->renderData["AccessLogList"]=$UriLogs;
    			$this->renderData["page"]=$page_str;
    		}
    		
    	}    	
    	$this->render("index",$this->renderData);
    }
}
?>