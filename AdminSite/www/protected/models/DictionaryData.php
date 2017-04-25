<?php

class DictionaryData {
	
	const Material_Files_File_Type=array(
			"1"		=>	"文本",
			"2"		=>	"音频",
			"3"		=>	"视频",
	);
	
	const Material_Files_File_Type_Text=1;
	const Material_Files_File_Type_Audio=2;
	const Material_Files_File_Type_Video=3;
	
	const Material_Files_Location_Type=array(
			"1"		=>	"站点内文本",
			"2"		=>	"站点内二进制",
			"3"		=>	"外站点URL",
	);
	
	const Material_Files_Location_Type_Text=1;
	const Material_Files_Location_Type_Binary=2;
	const Material_Files_Location_Type_OutUrl=3;
	
	const Task_Material_Task_Type=array(
			"1"		=>	"学习任务",
			"2"		=>	"陪伴任务",
	);
	
	const Task_Material_Task_Type_Learn=1;
	const Task_Material_Task_Type_Accompany=2;
	
	const Task_Material_Child_Gender=array(
			"1"		=>	"不限制",
			"2"		=>	"女孩",
			"3"		=>	"男孩",
	);
	
	const Task_Material_Child_Gender_Unlimited=1;
	const Task_Material_Child_Gender_Girl=2;
	const Task_Material_Child_Gender_Boy=3;
	
	const Task_Material_Parent_Gender=array(
			"1"		=>	"不限制",
			"2"		=>	"母亲",
			"3"		=>	"父亲",
	);

	const Task_Material_Parent_Gender_Unlimited=1;
	const Task_Material_Parent_Gender_Mother=2;
	const Task_Material_Parent_Gender_Father=3;
	
	const Task_Material_Parent_Marriage=array(
			"1"		=>	"不限",
			"2"		=>	"单亲",
	);
	
	const Task_Material_Parent_Marriage_Unlimited=1;
	const Task_Material_Parent_Marriage_Single=2;
	
	const Task_Material_Only_Children=array(
			"1"		=>	"不限",
			"2"		=>	"独生小孩",
	);
	
	const Task_Material_Only_Children_Unlimited=1;
	const Task_Material_Only_Children_One=2;
	
	const Task_Material_Task_Status=array(
			"1"		=>	"未发布",
			"2"		=>	"公开",
			"3"		=>	"灰度",
	);	
	const Task_Material_Task_Status_UnDeploy=1;
	const Task_Material_Task_Status_Publish=2;
	const Task_Material_Task_Status_Test=3;

		//分享渠道、账号来源
	const User_Share_To_Type=array(
			"1"		=>	"微信",
			"2"		=>	"微博",
			"3"		=>	"QQ",
	);	
	
}
