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
			"0"		=>	"不限制",
			"1"		=>	"女孩",
			"2"		=>	"男孩",
	);
	
	const Task_Material_Child_Gender_Unlimited=0;
	const Task_Material_Child_Gender_Girl=1;
	const Task_Material_Child_Gender_Boy=2;
	
	const Task_Material_Parent_Gender=array(
			"0"		=>	"不限制",
			"1"		=>	"母亲",
			"2"		=>	"父亲",
	);

	const Task_Material_Parent_Gender_Unlimited=0;
	const Task_Material_Parent_Gender_Mother=1;
	const Task_Material_Parent_Gender_Father=2;
	
	const Task_Material_Parent_Marriage=array(
			"0"		=>	"不限",
			"1"		=>	"单亲",
	);
	
	const Task_Material_Parent_Marriage_Unlimited=0;
	const Task_Material_Parent_Marriage_Single=1;
	
	const Task_Material_Only_Children=array(
			"0"		=>	"不限",
			"1"		=>	"独生小孩",
	);
	
	const Task_Material_Only_Children_Unlimited=0;
	const Task_Material_Only_Children_One=1;
	
}
