<?php

$template->templateSetVar('msg', '');
 
if (isset($_GET['code']))  {
    require_once('lib/Main.php');
    $json = json_decode(Main::requestURL("https://oauth.vk.com/access_token?client_id={$GLOBALS['vk_app_id']}&client_secret={$GLOBALS['vk_app_secret']}&code={$_GET['code']}&redirect_uri=". urlencode('http://'.$_SERVER['SERVER_NAME'].'/')), true);
    if (isset($json['error'])) 
        $template->templateSetVar('msg', 'Произошла ошибка при авторизации <br> Попробуйте еще раз.');
    else  {
        $user_id = intval($json['user_id']);
        $access_token = $json['access_token'];
        $id_facs = 0;
        $id_role = 0;
        $stmt = DataBase::query()->prepare("SELECT *  FROM `accounts` WHERE `id_vk` = ? LIMIT 1");
        $stmt->bindValue(1,  $user_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() != 0) 
        {
        	$t = $stmt->fetchAll(PDO::FETCH_ASSOC);
        	$id_facs = $t[0]['id_fac'];
        	$id_role = 1;
        }
        $stmt = DataBase::query()->prepare("SELECT *  FROM `users` WHERE `id_vk` = ? LIMIT 1");
        $stmt->bindValue(1,  $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            $userData = Main::requestVkApi("users.get","user_ids={$user_id}&fields=photo_100,sex&access_token={$access_token}");
            if (isset($userData)) {
                $photo = $userData[0]['photo_100'];
                $fName = $userData[0]['first_name'];
                $sex = $userData[0]['sex'];
            } else {
                $photo = "https://vk.com/images/deactivated_100.png";
                $fName = "Deleted";
                $sex = 1;
            }
            $secret =  GUID();
            $stmt = DataBase::query()->prepare("INSERT INTO `users` (`id_vk`,`photo`,`first_name`,`sex`,`GUID`,`id_role`)  VALUES (?,?,?,?,?,?)");
            $stmt->bindValue(1,  $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2,  $photo, PDO::PARAM_STR);
            $stmt->bindValue(3,  $fName, PDO::PARAM_STR);
            $stmt->bindValue(4,  $sex, PDO::PARAM_INT);
            $stmt->bindValue(5,  $secret, PDO::PARAM_STR);
            $stmt->bindValue(6,  $id_role, PDO::PARAM_INT);
            $stmt->execute();
            $stmt = DataBase::query()->prepare("DELETE FROM `accounts` WHERE `id_vk` = ? LIMIT 1");
            $stmt->bindValue(1,  $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt = DataBase::query()->prepare("SELECT `ID`,`GUID`  FROM `users` WHERE `id_vk` = ? LIMIT 1");
            $stmt->bindValue(1,  $user_id, PDO::PARAM_INT);
            try{$stmt->execute();}catch (PDOException $error) {trigger_error("Ошибка при работе с базой данных: {$error}");}
            $salt = getSalt();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $token = $salt . '_' . md5(join('_', array($data[0]['GUID'],  $salt)));
            setcookie("token",$token,0x6FFFFFFF);
            setcookie("id",$data[0]['ID'],0x6FFFFFFF);
            header( 'Location: /starter', true, 307 );
            die();
        } else {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (strlen($data[0]['GUID']) > 0) {
                if (isset($_COOKIE["token"])) {
                    $ex = explode("_",$_COOKIE["token"]);
                    if (md5(join('_', array($data[0]['GUID'],  $ex[0]))) == $ex[1]) {
                        header( 'Location: /starter', true, 307 );
                        die();
                    } else {
                        $template->templateSetVar('msg', 'Произошла ошибка при авторизации <br> Попробуйте еще раз.');
                        setcookie("token", "", time()-3600);
                        setcookie("id", "", time()-3600);  
                    }
                } else {
                    $salt = getSalt();
                    $token = $salt . '_' . md5(join('_', array($data[0]['GUID'],  $salt)));
                    setcookie("token",$token,0x6FFFFFFF);
                    setcookie("id",$data[0]['ID'],0x6FFFFFFF);
                    header( 'Location: /starter', true, 307 );
                    die();
                }
            } else {
                $secret =  GUID();
                $stmt = DataBase::query()->prepare("UPDATE `users` SET `GUID` = ? WHERE `ID` = ? ");
                $stmt->bindValue(1,  $secret, PDO::PARAM_STR);
                $stmt->bindValue(2,  $data[0]['ID'], PDO::PARAM_INT);
                try{$stmt->execute();}catch (PDOException $error) {trigger_error("Ошибка при работе с базой данных: {$error}");}
                $salt = getSalt();
                $token = $salt . '_' . md5(join('_', array($secret,  $salt)));
                setcookie("token",$token,0x6FFFFFFF);
                setcookie("id",$data[0]['ID'],0x6FFFFFFF);
                header( 'Location: /starter', true, 307 );
                die();
            }
        }
    }
}

$template->templateSetVar('auth_url', "https://oauth.vk.com/authorize?client_id={$GLOBALS['vk_app_id']}&redirect_uri=http://{$_SERVER['SERVER_NAME']}/&response_type=code&lang=ru&v=5.37");

$template->templateCompile();
$template->templateDisplay();

