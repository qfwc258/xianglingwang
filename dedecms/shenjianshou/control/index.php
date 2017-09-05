<?php   if(!defined('DEDEINC')) exit("Request Error!");

class index extends Control
{
    // 为兼容PHP4需使用同名函数作为析构
    public function index() {
        //载入模型
        parent::__construct();
        //载入模型
        $this->question = $this->Model('mquestion');
        $this->type = $this->Model('mtype');
        $this->scores = $this->Model('mscores');
        $this->answer = $this->Model('askanswer');
        $this->taConfig = $model = $this->Model('taconfig');
        $this->dsql = $this->taConfig->dsql;
    }

    public function ac_index() {
        global $targetany_config,$dede_charset;
        $postData = $this->mergeRequest();
        try {
            $this->ta_validation($postData);
            if (isset($postData['channel']) && $postData['channel'] && $postData['channel'] == 'article') {
                $this->insertArcticle($postData);
            } else if (isset($postData['channel']) && $postData['channel'] && $postData['channel'] == 'ask') {
                $this->insertAsk($postData);
            } else {
                ta_fail(TA_ERROR_MISSING_FIELD, "module params is not right", $dede_charset['msg']['fail_module_select']);
            }
        } catch (Exception $e) {
            ta_fail(TA_ERROR_ERROR, $e->getMessage(), $dede_charset['msg']['fail_insert_data']);
        }
    }

    public function ac_details() {
        global $GLOBALS,$dede_charset;
        $postData = $this->mergeRequest();
        $this->ta_validation($postData);

        $model = $this->Model('taConfig');
        try {
            if (isset($postData['channelType']) && $postData['channelType']) {
                $options = $model->getArctype($postData['channelType']);

                ta_success($options);
            } else if (isset($postData['askType']) && $postData['askType']) {
                $askExist = $model->askExist();
                if (!$askExist) {
                    ta_fail(TA_ERROR_PLUGIN_ERROR, "ask is not exist", $dede_charset['msg']['fail_module_exist']);
                }
                $options = $model->getAsktype();
                ta_success($options);
            }
        } catch (Exception $e) {
            ta_fail(TA_ERROR_ERROR, $e->getMessage(), $dede_charset['msg']['fail_detail']);
        }
    }

    public function ac_version() {
        global $targetany_config;
        $postData = $this->mergeRequest();
        $this->ta_validation($postData);

        ta_success($targetany_config);
    }

    private function ta_validation($postData) {
        global $dede_charset;
        $model = $this->Model('taConfig');
        $password = $model->getConfig('password');
        if (!($postData && isset($postData['__sign']) && $postData['__sign']) || !(isset($password[0]) && isset($password[0]['value']) && $password[0]['value']) || $postData['__sign'] !== $password[0]['value']) {
            ta_fail(TA_ERROR_INVALID_PWD, "password is wrong", $dede_charset['msg']['fail_passwd_wrong']);
        }

        return true;
    }

    private function insertArcticle($postData) {
        global $cfg_phpurl, $cfg_title_maxlen, $cfg_rewrite,$dede_charset;
        try {
            require_once(DEDEINC.'/image.func.php');
            require_once(DEDEINC.'/customfields.func.php');
            require_once(DEDEASK.'/libraries/inc_archives_functions.php');
            if(file_exists(DEDEDATA.'/template.rand.php'))
            {
                require_once(DEDEDATA.'/template.rand.php');
            }
            if(!isset($autokey)) $autokey = 0;
            if(!isset($remote)) $remote = 0;
            if(!isset($dellink)) $dellink = 0;
            if(!isset($autolitpic)) $autolitpic = 0;

            if (!isset($postData['channelType']) || !$postData['channelType'])
            {
                ta_fail(TA_ERROR_MISSING_FIELD, "channelType is not exist", $dede_charset['msg']['fail_channel_exist']);
            }
            $model = $this->Model('taConfig');
            $this->dsql = $model->dsql;
            if(!$this->CheckChannel($postData['category'], $postData['channelType']))
            {
                ta_fail(TA_ERROR_MISSING_FIELD, "category is not match", $dede_charset['msg']['fail_category_match']);
            }

            $typeid = $postData['category'];
            $typeid2 = '';

            //对保存的内容进行处理
            $pubdate = isset($postData['article_publish_time']) && $postData['article_publish_time'] ? $postData['article_publish_time']: GetMkTime(time());
            $title = isset($postData['article_title']) && $postData['article_title'] ? $postData['article_title']: '';
            $shorttitle = isset($postData["article_subtitle"]) && $postData["article_subtitle"] ? $postData["article_subtitle"] :'';
            if(!empty($postData["article_author"])){
                if($postData["article_author"] == "author_web_master"){
                    $row = $this->dsql->GetOne("SELECT uname FROM `#@__admin`");
                    $writer = $row["uname"];
                }else if($postData["article_author"] == "author_existing_users"){
                    $row = $this->dsql->GetOne("SELECT uname FROM `#@__member` ORDER BY rand()");
                    $writer = $row["uname"];
                }else{
                    $writer = $postData["article_author"];
                }
            }else{
                $writer = $dede_charset['network'];
            }
            $source = isset($postData["article_origin_from"]) && $postData["article_origin_from"] ? $postData["article_origin_from"] :$dede_charset['unknown'];
            $description = isset($postData["article_brief"]) && $postData["article_brief"] ? $postData["article_brief"] :'';
            $typeid = isset($postData["category"]) && $postData["category"] ? $postData["category"] :'';
            $channelid = isset($postData['channelType']) && $postData['channelType'] ? $postData['channelType'] : '1';
            if (isset($postData["article_topics"]) && $postData["article_topics"]) {
                $topics = json_decode($postData["article_topics"], true);
                $keywords = is_array($topics) && count($topics) ? implode(',', $topics): '';
            }
            $click = isset($postData['article_view_count']) && $postData['article_view_count'] ? $postData['article_view_count']: mt_rand(50, 200);
            $body = isset($postData['article_content']) && $postData['article_content'] ? $postData['article_content']: '';
            $picname = isset($postData['article_thumbnail']) && $postData['article_thumbnail'] ? $postData['article_thumbnail']: '';
            $notpost = isset($notpost) && $notpost == 1 ? 1: 0;
            $userip = GetIP();
            $serviterm=empty($serviterm)? "" : $serviterm;

            try {
                $adminid = $this->getUser($writer);
            } catch (Exception $e) {
                ta_fail(TA_ERROR_PLUGIN_ERROR, $e->getMessage(), $dede_charset['msg']['fail_insert_user']);
            }

            $arcrank = 0;
            $sortrank = AddDay($pubdate, 0);
            $ismake = 0;
            $autokey = 1;
            $voteid = '0';
            $senddate = time();
            $title = dede_htmlspecialchars(cn_substrR($title, $cfg_title_maxlen));
            $shorttitle = cn_substrR($shorttitle,36);
            $color =  '';//cn_substrR($color,7);
            $writer =  cn_substrR($writer,20);
            $source = cn_substrR($source,30);
            $description = cn_substrR($description,250);
            $tags = $keywords = trim(cn_substrR($keywords,60));
            $filename = '';
            $isremote  = 1;

            $arcID = GetIndexKey($arcrank,$typeid,$sortrank,$channelid,$senddate,$adminid['uid']);

            $weight = isset($postData['weight']) && $postData['weight'] >= 0 ? $postData['weight'] : $arcID;
            $litpic = '';

            if(empty($arcID))
            {
                ta_fail(TA_ERROR_MISSING_FIELD, "arcID is wrong", $dede_charset['msg']['fail_arcID']);
            }
            if(trim($title) == '')
            {
                ta_fail(TA_ERROR_MISSING_FIELD, "article_title is wrong", $dede_charset['msg']['fail_title_empty']);
            }
            //处理body字段自动摘要、自动提取缩略图等
            $body = AnalyseHtmlBody($body,$description,$litpic,$keywords,'htmltext');
            if(!empty($picname)){
                $litpic = $picname;
            }
            //处理图片文档的自定义属性
            if($redirecturl!='' && !preg_match("#j#", $flag))
            {
                $flag = ($flag=='' ? 'j' : $flag.',j');
            }
            //保存到主表
            $query = "INSERT INTO `#@__archives`(id,typeid,typeid2,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,
            color,writer,source,litpic,pubdate,senddate,mid,voteid,notpost,description,keywords,filename,dutyadmin,weight)
            VALUES ('$arcID','$typeid','$typeid2','$sortrank','$flag','$ismake','$channelid','$arcrank','$click','$money',
            '$title','$shorttitle','$color','$writer','$source','$litpic','$pubdate','$senddate',
            '{$adminid['uid']}','$voteid','$notpost','$description','$keywords','$filename','{$adminid['username']}','$weight');";


            $basic_web_address = str_replace('\\', '/', $_SERVER['HTTP_HOST']);
            $ta_unique = $model->getConfig('unique');
            if(!empty($ta_unique) && $ta_unique[0]['value'] == '1'){
                $result = $this->dsql->GetOne("SELECT * FROM {$this->dsql->dbPrefix}archives WHERE title='{$title}'");
                if($result){
                    $artUrl = $cfg_phpurl."/view.php?aid={$result['id']}";
                    ta_success(array("url" => 'http://'.$basic_web_address.$artUrl));
                }
            }
            if(!$this->dsql->ExecuteNoneQuery($query))
            {
                $gerr = $this->dsql->GetError();
                $this->dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
                ta_fail(TA_ERROR_MISSING_FIELD, "arcID is wrong", $dede_charset['msg']['fail_save_archives'].str_replace('"','',$gerr),"javascript:;");
            }
            //保存到附加表
            $cts = $this->dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id='$channelid' ");
            $addtable = trim($cts['addtable']);
            if(empty($addtable))
            {
                $this->dsql->ExecuteNoneQuery("DELETE FROM `#@__archives` WHERE id='$arcID'");
                $this->dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
                ta_fail(TA_ERROR_MISSING_FIELD, "arcID is wrong", str_replace("#",$channelid,$dede_charset['msg']['fail_model_empty']),"javascript:;");
            }
            $useip = GetIP();

            $templet = empty($templet) ? '' : $templet;
            $query = "INSERT INTO `{$addtable}`(aid,typeid,redirecturl,templet,userip,body) Values('$arcID','$typeid','$redirecturl','$templet','$useip','$body')";
            if(!$this->dsql->ExecuteNoneQuery($query))
            {
                $gerr = $this->dsql->GetError();
                $this->dsql->ExecuteNoneQuery("Delete From `#@__archives` where id='$arcID'");
                $this->dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
                ta_fail(TA_ERROR_MISSING_FIELD,str_replace("#",$addtable,$dede_charset['msg']['fail_save_table']).str_replace('"','',$gerr),"javascript:;");
            }
            //生成HTML
            InsertTags($tags,$arcID);
            $picTitle = false;
            $artUrl = MakeArt($arcID,true,true,$isremote);
            if($artUrl=='')
            {
                $artUrl = $cfg_phpurl."/view.php?aid=$arcID";
            }
            ClearMyAddon($arcID, $title);

            $this->updateCatch($typeid);
            $parentid = $this->getParentCategory($typeid);
            if ($parentid) {
                $this->updateCatch($parentid);
            }

            ta_success(array("url" => 'http://'.$basic_web_address.$artUrl));
        } catch (Exception $e) {
            ta_fail(TA_ERROR_ERROR, $e->getMessage(), $dede_charset['msg']['fail_insert_article']);
        }
    }

    private function insertAsk($postData) {
        global $GLOBALS, $cfg_indexurl,$dede_charset;
        try {
            $model = $this->Model('taConfig');
            $this->dsql = $model->dsql;
            $data['title'] = isset($postData['question_title']) && $postData['question_title'] ? strip_tags($postData['question_title']): '';
            $data['content'] = isset($postData['question_detail']) && $postData['question_detail'] ? strip_tags($postData['question_detail']): '';
            $data['anonymous'] = isset($postData['anonymous']) && $postData['anonymous'] ? $postData['anonymous'] : '';
            $data['reward'] = 0;
            if(!empty($postData["question_author"])){
                if($postData["question_author"] == "author_web_master"){
                    $row = $this->dsql->GetOne("SELECT uname FROM `#@__admin`");
                    $writer = $row["uname"];
                }else if($postData["question_author"] == "author_existing_users"){
                    $row = $this->dsql->GetOne("SELECT uname FROM `#@__member` ORDER BY rand()");
                    $writer = $row["uname"];
                }else{
                    $writer = $postData["question_author"];
                }
            }else{
                $writer = $dede_charset['anonymity'];
            }
            if (isset($postData['question_answer']) && $postData['question_answer']) {
                $answers = json_decode($postData['question_answer'], true);
            }

            if($data['title'] == '')
            {
                ta_fail(TA_ERROR_MISSING_FIELD, 'question_title is null', $dede_charset['msg']['fail_question_title']);
            }
            //检查问题内容
            if(empty($data['content']))
            {
                ta_fail(TA_ERROR_MISSING_FIELD, 'question_detail is null', $dede_charset['msg']['fail_question_detail']);
            }

            $data['scores'] = 9990;

            $data['faqkey'] = 1;
            $data['vdcode'] = request('vdcode', '');
            $data['safeanswer'] = request('safeanswer', '');

            $typeid = isset($postData["category"]) && $postData["category"] ? $postData["category"] : '1';

            $parentType = $this->getParentCategory($typeid, 0);

            $ClassLevel1 = $parentType ? $parentType : $typeid;
            $ClassLevel2 = $parentType ? $typeid: '';

            try {
                $adminid = $this->getUser($writer);
            } catch (Exception $e) {
                ta_fail(TA_ERROR_MISSING_FIELD, $e->getMessage(), $dede_charset['msg']['fail_insert_user'].":".$writer);
            }

            $data['uid'] = $adminid['uid'];

            $data['timestamp'] = isset($postData['question_publish_time']) && $postData['question_publish_time'] ? $postData['question_publish_time'] : time() - 86400;
            $data['scores'] = 0;
            //检查问题名称


            $data['title'] = preg_replace("#{$GLOBALS['cfg_replacestr']}#","***",HtmlReplace($data['title'], 1));
            $data['content']  = preg_replace("#{$GLOBALS['cfg_replacestr']}#","***",HtmlReplace($data['content'], -1));
            $data['anonymous'] = (!empty($data['anonymous'])) ? 1 : 0;
            $data['tid'] = $data['tid2']  = 0;
            $data['tidname'] = $data['tid2name'] = '';
            $data['userip'] = getip();
            $data['reward'] = intval($data['reward']);
            if($data['reward'] < 0) $data['reward'] = 0;
            //处理栏目
            $ClassLevel1 = intval($ClassLevel1);
            if($ClassLevel1 < 1)
            {
                ta_fail(TA_ERROR_MISSING_FIELD, 'category is null', $dede_charset['msg']['fail_category_exist']);
            }
            $ClassLevel2 = intval($ClassLevel2);
            if($ClassLevel2 != 0) $where = " WHERE id in ($ClassLevel1,$ClassLevel2)";
            else $where = "WHERE id='$ClassLevel1'";
            $rows = $this->type->get_asktype($where);
            foreach ($rows as $row) {
                if($row['id'] == $ClassLevel1)
                {
                    $data['tidname'] = $row['name'];
                    $data['tid'] = $row['id'];
                }elseif($row['id'] == $ClassLevel2 && $row['reid'] == $ClassLevel1){
                    $data['tid2name'] = $row['name'];
                    $data['tid2'] = $row['id'];
                }
            }

            //计算过期时间
            $data['expiredtime'] = $GLOBALS['cfg_ask_timestamp'] + 86400 * $GLOBALS['cfg_ask_expiredtime'];
            //保存问题

            $this->question->dsql = $this->dsql;
            $rs = $this->question->save_ask($GLOBALS['cfg_ask_ifcheck'],$data);
            $id = $this->question->dsql->GetLastID();
            if($rs)
            {
                //获取最大的id
                $maxid = $this->question->get_maxid($GLOBALS['cfg_ask_timestamp']);
                //更新栏目统计信息
                $this->type->update_asktype($data['tid']);
                if($data['tid2'] > 0) $this->type->update_asktype($data['tid2']);
                //积分处理
                $this->scores->update_scores($data['uid'],$needscore);
                //清理附加的缓存，并将id写入数据库
                clearmyaddon($maxid, $data['title']);
            } else {
                ta_fail(TA_ERROR_MISSING_FIELD, 'question is fail',  $dede_charset['msg']['fail_question']);
            }

            if (is_array($answers) && count($answers)) {
                $this->dsql = $this->question->dsql;
                $step = 86400/count($answers);
                foreach ($answers as $answer) {
                    $this->insertAnswer($answer, $id, $data['anonymous'], $data['timestamp'] + $step);
                }
            }
            $artUrl = 'http://' . str_replace('\\', '/', $_SERVER['HTTP_HOST']).$cfg_indexurl."/ask/?ct=question&askaid=". $id;

            ta_success(array("url" => $artUrl));
        } catch (Exception $e) {
            ta_fail(TA_ERROR_ERROR, $e->getMessage(), $dede_charset['msg']['fail_question_insert']);
        }
    }

    private function insertAnswer($answer, $aid, $anonymous = 0, $publishtime = 0) {
        global $GLOBALS,$dede_charset;
        try {
            $content = isset($answer['question_answer_content']) && $answer['question_answer_content'] ? $answer['question_answer_content'] : '';
            $data['askaid'] = is_numeric($aid)? $aid : 0;
            $authorExist = isset($answer['question_answer_author']) && $answer['question_answer_author'] ? 0 : 1;
            if (!$authorExist) {
                $answerAuthor = $this->getUser($answer['question_answer_author']);
            }

            //检查是否已经存在答复
            $rs = $this->answer->get_answer($answerAuthor['uid'], $data['askaid']);

            if($content == '')
            {
                //ta_fail(TA_ERROR_MISSING_FIELD, 'question_answer_content is null', "回答不能为空!");
                return false;
            }else if(strlen($content) > 10000)
            {
                return false;
                //ta_fail(TA_ERROR_ERROR, 'question_answer_content is too long', "回答太长!");
            }

            //获取问题的基本信息
            $wheresql = "id='{$data['askaid']}'";
            $field = "tid, tid2, uid, dateline, expiredtime, solvetime";
            $question = $this->question->get_one($wheresql,$field);
            if($question)
            {
                $data['tid'] = $question['tid'];
                $data['tid2'] = $question['tid2'];
                $data['userip'] = getip();
            }else{

            }

            $data['anonymous'] = 0;
            if($GLOBALS['cfg_ask_guestanswer'] == 'Y')
            {
                $data['anonymous'] = empty($anonymous)? 0 : 1;
            }

            $data['content'] = isset($content) ? preg_replace("#{$GLOBALS['cfg_replacestr']}#","***",HtmlReplace($content, -1)) : '';
            $data['uid'] = $answerAuthor['uid'];
            $data['username'] = $answerAuthor['username'];
            if (isset($answer['question_answer_publish_time']) && $answer['question_answer_publish_time']) {
                $data['timestamp'] = $answer['question_answer_publish_time'];
            } else {
                $data['timestamp'] = $publishtime ? $publishtime : rand(time() - 86400, time());
            }

            //保存回复
            $this->answer->dsql = $this->dsql;
            $rs = $this->answer->save_answer($GLOBALS['cfg_ask_ifkey'],$data);

            if(!$rs){
                ta_fail(TA_ERROR_ERROR, 'question_answer save fail', $dede_charset['msg']['fail_question_save']);
            }
            //获取回复的最大id
            $maxid = $this->answer->get_maxid($data['timestamp']);
            $ids = array($data['askaid'],$maxid);
            clearmyaddon($ids, "回复");

            //回复数增加
            $rs = $this->question->update_ask("replies=replies+1","id='{$data['askaid']}'");
            $rs = $this->question->update_ask("lastanswer=".time(),"id='{$data['askaid']}'");
            $answerscore = intval($GLOBALS['cfg_ask_answerscore']);

            //只要回答问题就增加积分
            if($GLOBALS['cfg_ask_ifanscore'] == 'Y') $this->scores->add_scores($data['uid'],$GLOBALS['cfg_ask_answerscore']);
        } catch (Exception $e) {
            ta_fail(TA_ERROR_ERROR, $e->getMessage(), $dede_charset['msg']['fail_answer_insert']);
        }
        return true;
    }

    private function getParentCategory($typeid, $type = 1) {
        $parent = '';
        if ($type) {
            $this->dsql->SetQuery("SELECT * FROM `#@__arctype` where id='".$typeid."'");
            $this->dsql->Execute();
            while($row = $this->dsql->GetArray())
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

    private function getUser($username) {
        global $cfg_mb_wnameone, $cfg_md_mailtest, $cfg_mb_spacesta,$dede_charset;
        $uid = '';
        $username = HtmlReplace($username, 1);
        $email = $this->randEmail(substr(md5($username), 7, 13));
        $row = $this->dsql->GetOne("SELECT * FROM `#@__member` WHERE userid = '$username' ");
        if(is_array($row) && !empty($row))
        {
            return array('uid' => $row['mid'], 'username' => $username);
        }
        try {
            if($cfg_mb_wnameone=='N') {
                $row = $this->dsql->GetOne("SELECT * FROM `#@__member` WHERE uname LIKE '$username' ");
                if(is_array($row)) {
                    ta_fail(TA_ERROR_ERROR, 'username is repeat', $dede_charset['msg']['fail_username_repeat']);
                }
            }
            if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php') {
                $uid = uc_user_register($username, '123456', $email);
                if($uid <= 0)
                {
                    if($uid == -1)
                    {
                        ta_fail(TA_ERROR_ERROR, 'username is -1', $dede_charset['msg']['fail_username']);
                    }
                    elseif($uid == -2)
                    {
                        ta_fail(TA_ERROR_ERROR, 'username is -2', $dede_charset['msg']['fail_username_words']);
                    }
                    elseif($uid == -3)
                    {
                        ta_fail(TA_ERROR_ERROR, 'username is -3', str_replace("#",$username,$dede_charset['msg']['fail_username_exist']));
                    }
                    elseif($uid == -5)
                    {
                        ta_fail(TA_ERROR_ERROR, 'username is -5', $dede_charset['msg']['fail_username_email']);
                    }
                    elseif($uid == -6)
                    {
                        ta_fail(TA_ERROR_ERROR, 'username is -6', $dede_charset['msg']['fail_username_locked']);
                    }
                    else
                    {
                        ta_fail(TA_ERROR_ERROR, 'username is error', $dede_charset['msg']['fail_register']);
                    }
                }
                else
                {
                    $ucsynlogin = uc_user_synlogin($uid);
                }
            }

            //会员的默认金币
            $dfscores = 0;
            $dfmoney = 0;
            $rands = array(10, 50, 100);

            $dfrank = $this->dsql->GetOne("SELECT money,scores FROM `#@__arcrank` WHERE rank='".$rands[rand(0,2)]."' ");
            if(is_array($dfrank))
            {
                $dfmoney = $dfrank['money'];
                $dfscores = $dfrank['scores'];
            }
            $jointime = time();
            $logintime = time();
            $joinip = GetIP();
            $loginip = GetIP();
            $pwd = md5('123456');
            $mtype = RemoveXSS(HtmlReplace('个人',1));
            $safeanswer = '';
            $safequestion = '';

            $spaceSta = ($cfg_mb_spacesta < 0 ? $cfg_mb_spacesta : 0);

            $inQuery = "INSERT INTO `#@__member` (`mtype` ,`userid` ,`pwd` ,`uname` ,`sex` ,`rank` ,`money` ,`email` ,`scores` ,
            `matt`, `spacesta` ,`face`,`safequestion`,`safeanswer` ,`jointime` ,`joinip` ,`logintime` ,`loginip` )
           VALUES ('$mtype','$username','$pwd','$username','','10','$dfmoney','$email','$dfscores',
           '0','$spaceSta','','$safequestion','$safeanswer','$jointime','$joinip','$logintime','$loginip'); ";
            if($this->dsql->ExecuteNoneQuery($inQuery))
            {
                $mid = $this->dsql->GetLastID();

                //写入默认会员详细资料
                if($mtype== $dede_charset['person']){
                    $space='person';
                }else if($mtype== $dede_charset['company']){
                    $space='company';
                }else{
                    $space='person';
                }

                //写入默认统计数据
                $membertjquery = "INSERT INTO `#@__member_tj` (`mid`,`article`,`album`,`archives`,`homecount`,`pagecount`,`feedback`,`friend`,`stow`)
                       VALUES ('$mid','0','0','0','0','0','0','0','0'); ";
                $this->dsql->ExecuteNoneQuery($membertjquery);

                //写入默认空间配置数据
                $spacequery = "INSERT INTO `#@__member_space`(`mid` ,`pagesize` ,`matt` ,`spacename` ,`spacelogo` ,`spacestyle`, `sign` ,`spacenews`)
                        VALUES('{$mid}','10','0','".$username.$dede_charset['tpl']['space_text']."','','$space','',''); ";
                $this->dsql->ExecuteNoneQuery($spacequery);

                //写入其它默认数据
                $this->dsql->ExecuteNoneQuery("INSERT INTO `#@__member_flink`(mid,title,url) VALUES('$mid','".$dede_charset['dedecms']."','http://www.dedecms.com'); ");
            } else {
                ta_fail(TA_ERROR_ERROR, 'reg fail', $dede_charset['msg']['fail_register']);
            }

        } catch (Exception $exc) {
            ta_fail(TA_ERROR_ERROR, 'reg fail', $exc->getMessage());
        }

        return array('uid' => $mid, 'username' => $username);
    }

    private function randEmail($username) {
        $emailSps = array('163.com', 'qq.com', 'gmail.com', 'sina.com', 'weibo.com', 'yahoo.cn', '139.com');
        $f = substr(md5($username),8,rand(6, 12));
        return $f . '@' . $emailSps[rand(0, 6)];
    }

    private function updateCatch($typeid) {
        global $cfg_Cs;
        $this->dsql->ExecuteNoneQuery("Delete From `#@__arccache` ");
        if($cfg_Cs[$typeid][1]>0)
        {
            require_once(DEDEINC."/arc.listview.class.php");
            $lv = new ListView($typeid);
        }
        else
        {
            require_once(DEDEINC."/arc.sglistview.class.php");
            $lv = new SgListView($typeid);
        }
        $lv->CountRecord();
        $lv->MakeHtml();
    }

    private function mergeRequest() {
        global $cfg_db_language;
        $requestData = $postData = array();
        if (isset($GLOBALS['request']) && $GLOBALS['request']->posts) {
            $requestData = array_merge($GLOBALS['request']->posts, $GLOBALS['request']->gets);
        }

        foreach ($requestData as $key => $value) {
            if(!in_array(strtolower($cfg_db_language) ,array('utf8','utf-8')) ){
                $postData[$key] = iconv('UTF-8', $cfg_db_language, urldecode($value));
            }else{
                $postData[$key] = urldecode($value);
            }
        }
        return $postData;
    }

    private function CheckChannel($typeid, $channelid)
    {
        if($typeid==0) return TRUE;
        $row = $this->dsql->GetOne("SELECT ispart,channeltype FROM `#@__arctype` WHERE id='$typeid' ");

        if($row['ispart']!=0 || $row['channeltype'] != $channelid) return FALSE;
        else return TRUE;
    }
}
?>