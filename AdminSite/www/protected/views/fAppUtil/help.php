<div class="help_wrap">
	<ul class="help_box">
	
<?php
$rowsHtmlTag=<<<EndOfRowTag
	<li>
    	<div class="ask_box">
        	<h3><i class="icon_ask">问</i><p>%s</p></h3>
        </div>
        <div class="answer_box">
        	<i class="icon_answer">答</i>
            	<p>
					%s
				</p>
                        
		</div>
	</li>
EndOfRowTag;
		foreach ($HelpItems as $item){	
			echo sprintf($rowsHtmlTag,$item["q"],$item["a"]);
		}
?>
            </ul>
        </div>    