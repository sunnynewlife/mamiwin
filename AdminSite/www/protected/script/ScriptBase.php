<?php


class  ScriptBase extends CConsoleCommand
{
    
        protected $level = "info";
        protected $cat = "";

         /*
         *
         */
        public function setlevel($level=null)
        {
            if($level){
                $this->level = $level;
            }
        }


        /*
         * 
         */
        public function setcate($cat=null)
        {
                $this->cat = $cat;
        }

        /*
         * report the status
         * @params $message
         *         $cat
        */
        public function statusRepertDB($message,$cat="")
        {
            if($cat){
                $category = $cat;
            }else{
                $category = $this->cat;
            }
            Yii::log($message, $this->level, $category);
        }

        /*
         * mailManager the error
         * @params $content
         *         $mail_title
         */
        public function statusMailManager($content,$mail_title=null)
        {
            
            $mail_list = Yii::app()->params['data_alert_mail'];
            if(empty($mail_title)){
                $t = "";
                if(G_CODE_ENV == 'DEV' || G_CODE_ENV == 'TEST')
                {
                    $mail_title = "测试邮件,请忽略";
                }else{
                    $mail_title = $this->cat.'脚本出错，请查看日志，获取详细信息';
                }
            }

            $mailContent = $content;
            CommunicationHelper::sendEmail($mail_list,  $mail_title, $mailContent);
        }

    public function getCategory()
    {
        $return = "";
        $args = func_get_args();
        return implode(".",$args);
    }
}