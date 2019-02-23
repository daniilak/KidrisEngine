<?php
require_once('lib/TemplateEngine.php');
require_once('lib/DataBase.php');
if (isset($_COOKIE["token"]) && isset($_COOKIE["id"])) 
{
	header( 'Location: /starter', true, 307 );
	die();
}
$db = new DataBase();
$template = new TemplateEngine("page/auth.tpl");
$template->templateSetVar('msg', '');
 function GUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
function getSalt() {
    return sprintf('%04u%04u%04u%04u%04u%04u%04u%04u', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
function verifyPassword($pass,$hash)
{
	return (password_verify($pass, $hash)) ? true : false;
}
if (isset($_POST['g-recaptcha-response']) && isset($_POST['password']) && isset($_POST['login_auth']) ) 
{
	
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$GLOBALS['reCAPTCHA']."&response=".$_POST['g-recaptcha-response']);
	$response = json_decode($response, true);
	if($response["success"] === false) 
	{
		$template->templateSetVar('form', '<div class="social-auth-links text-center"> <a href="{auth_url}" class="btn btn-block btn-social btn-facebook btn-flat">
			<i class="fa fa-vk"></i> Войти через ВКонтакте</a></div>');
		$template->templateSetVar('msg', 'Произошла ошибка при авторизации <br> Попробуйте еще раз.');
		$template->templateSetVar('auth_url', $GLOBALS['auth_url'] );
		$template->templateCompile();
		$template->templateDisplay();
		die();
	}
	$password 	= trim(strip_tags($_POST['password']));
	$login 		= trim(strip_tags($_POST['login_auth']));
	$stmt = DataBase::query()->prepare("SELECT *  FROM `users` WHERE `id_vk` = ? LIMIT 1");
    $stmt->bindValue(1,  $login, PDO::PARAM_INT);
    $stmt->execute();
	if ($stmt->rowCount() == 0)
	{
		$template->templateSetVar('form', '<div class="social-auth-links text-center"> <a href="{auth_url}" class="btn btn-block btn-social btn-facebook btn-flat">
			<i class="fa fa-vk"></i> Войти через ВКонтакте</a></div>');
		$template->templateSetVar('msg', 'Такого пользователя не существует');
		$template->templateSetVar('auth_url', $GLOBALS['auth_url'] );
		$template->templateCompile();
		$template->templateDisplay();
		die();
	}
	$data = $stmt->fetchAll();
	
	if (!verifyPassword($password,$data[0]['pass_hash'])) 
	{
		$template->templateSetVar('form', '<div class="social-auth-links text-center"> <a href="{auth_url}" class="btn btn-block btn-social btn-facebook btn-flat">
			<i class="fa fa-vk"></i> Войти через ВКонтакте</a></div>');
		$template->templateSetVar('msg', 'Логин или пароль неправильные');
		$template->templateSetVar('auth_url', $GLOBALS['auth_url'] );
		$template->templateCompile();
		$template->templateDisplay();
		die();
	}
	$salt = getSalt();
    $token = $salt . '_' . md5(join('_', array($data[0]['GUID'],  $salt)));
    setcookie("token",$token,0x6FFFFFFF);
     setcookie("id",$data[0]['ID'],0x6FFFFFFF);
    header( 'Location: /starter', true, 307 );
    die();
}


$template->templateSetVar('auth_url', "https://oauth.vk.com/authorize?client_id={$GLOBALS['vk_app_id']}&redirect_uri=http://snochuvsu.ru/&response_type=code&lang=ru&v=5.37");
$template->templateSetVar('form', '<div class="social-auth-links text-center"> <a href="{auth_url}" class="btn btn-block btn-social btn-facebook btn-flat">
			<i class="fa fa-vk"></i> Войти через ВКонтакте</a></div>');
			
$template->templateCompile();
$template->templateDisplay();

