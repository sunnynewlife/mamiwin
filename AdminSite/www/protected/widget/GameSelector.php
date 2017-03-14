<?php
class GameSelector extends CWidget
{
	public function run() 
	{
		
		$fAppData=new FAppData();
		$App=$fAppData->getApp();
		$renderData=array("game" => $App);
        $this->render('gameSelector',$renderData);        
    }
}

