<?php   if(!defined('DEDEINC')) exit("Request Error!");
class configController extends Control
{
    function configController()
    {
        parent::__construct();
        //获取url
        $this->currurl = GetCurUrl();
        //获取类别
        $this->style = 'admin';
        //载入模型
        $this->config = $this->Model('taconfig');
    }

    //编辑
    function ac_index()
    {
        global $targetany_config;
        $basic_web_address = str_replace('\\', '/', $_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['SCRIPT_NAME'])));
        $password = $this->config->getConfig('password');
        $unique = $this->config->getConfig('unique');
        if(is_array($unique) && !empty($unique)) {
            $GLOBALS['ta_unique'] = $unique[0]['value'];
            $GLOBALS['ta_unique_pid'] = $unique[0]['id'];
        }else{
            $GLOBALS['ta_unique'] = 0;
            $GLOBALS['ta_unique_pid'] = $password[0]['id']+1;
        }

        $GLOBALS['ta_password'] = isset($password[0]) && isset($password[0]['value']) ? $password[0]['value']: '';
        $GLOBALS['ta_password_pid'] = $password[0]['id'];
        $GLOBALS['basic_web_address'] = $basic_web_address;
        $GLOBALS['taconfig'] = $targetany_config;
        //载入模板
        $this->SetTemplate('shenjianshou_config.htm');
        $this->Display();
    }

    //保存编辑
    function ac_edit_save()
    {
        global $dede_charset;
        $data['ta_password'] = request('ta_password', '');
        $data['ta_password_pid'] = request('ta_password_pid', '');
        $data['ta_unique'] = request('ta_unique','');
        $data['ta_unique_pid'] = request('ta_unique_pid','');

        if(trim($data['ta_password']) == "")
        {
            ShowMsg($dede_charset['msg']['fail_passwd_empty'],'?ct=configController');
            exit();
        }
        $rs = $this->config->setConfig('password', trim($data['ta_password']), $data['ta_password_pid']);
        $this->config->setConfig('unique', trim($data['ta_unique']),$data['ta_unique_pid']);

        if($rs){
                ShowMsg($dede_charset['msg']['success_setting'],'?ct=configController');
                exit();
        }else{
                ShowMsg($dede_charset['msg']['success_setting'],'?ct=configController');
                exit();
        }
    }
}
?>