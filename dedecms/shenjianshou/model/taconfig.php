<?php   if(!defined('DEDEINC')) exit("Request Error!");

class taconfig extends Model
{
    /**
     * 得到信息
     *
     * @param     string    $configKey
     * @return    array
     */
    function getConfig($configKey = "")
    {

        $query = "SELECT * FROM `#@__taconfig` where `key` = '" . mysql_escape_string(addslashes($configKey)) . "'";
        $this->dsql->SetQuery($query);
        $this->dsql->Execute();
        $configs = array();
        while($config = $this->dsql->GetArray())
        {
            $configs[] = $config;
        }

        return $configs;
    }

   /**
     * 更新一个配置
     *
     * @param     int    $tid
     * @return    int
     */
    function setConfig($configKey = "", $value = '', $id = 0)
    {
        if($configKey)
        {
            $query = "replace into `#@__taconfig` (`id`, `key`, `value`) values('" . mysql_escape_string(addslashes($id)) . "','" . mysql_escape_string(addslashes($configKey)) . "', '" . mysql_escape_string(addslashes($value)) . "')";

            $this->dsql->ExecuteNoneQuery($query);
            return TRUE;
        }else{
            return FALSE;
        }

    }

    /**
     * 保存一个新增配置
     * @param     array  $data
     * @return    string
     */
    function save_add($data = array()) {
        if(is_array($data))
        {
            $query = "INSERT INTO `#@__taconfig`(key, value) VALUES('{$data['key']}','{$data['value']}');";
    		if($this->dsql->ExecuteNoneQuery($query)) return TRUE;
            else return FALSE;
        }else{
            return FALSE;
        }
    }

    function getArctype($channelType) {
        global $dede_charset,$cfg_db_language;
        $this->dsql->Execute('me', "SELECT * FROM `#@__arctype` where channeltype='".$channelType."'");
        $options = array();
        while($row = $this->dsql->GetArray('me'))
        {
            if ($row['typename'] != $dede_charset['question']) {
                $typename = ta_iconv($row['typename']);
                $options[] = array('value' => $row['id'], 'text' => $typename);
            }
        }

        return $options;
    }

    function getParentCategory($typeid, $type = 1) {
        $parent = '';
        if ($type) {
            $this->dsql->Execute('me', "SELECT * FROM `#@__arctype`");
            while($row = $this->dsql->GetArray('me'))
            {
                $parent = $row['reid'];
            }
        } else {
            $this->dsql->SetQuery("SELECT * FROM `#@__asktype` where id='".$typeid."'");
            $this->dsql->Execute();
            while($row = $this->dsql->GetArray())
            {
                $parent = $row['reid'];
            }
        }

        return $parent;
    }

    function getAsktype() {
        global $dede_charset,$cfg_db_language;
        $this->dsql->SetQuery("SELECT * FROM `#@__asktype`");
        $this->dsql->Execute();
        $options = array();
        while($row = $this->dsql->GetArray())
        {
            $name = ta_iconv($row['name']);
            $options[] = array('value' => $row['id'], 'text' =>$name);
        }

        return $options;
    }

    function askExist() {
        global $dede_charset;
        $this->dsql->SetQuery("SELECT * FROM `#@__sys_module` where modname='{$dede_charset['module_question']}'");
        $this->dsql->Execute();
        $row = $this->dsql->GetObject();
        if (!$row || !$row->hashcode) {
            return false;
        }
        return true;
    }


}