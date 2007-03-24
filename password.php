<?php
//
// File: password.php
//
// Template File: password.tpl
//
// Template Variables:
//
// tMessage
//
// Form POST \ GET Variables:
//
// fPassword_current
// fPassword
// fPassword2
//
require ("./variables.inc.php");
require ("./config.inc.php");
require ("./functions.inc.php");
include ("./languages/" . $CONF['language'] . ".lang");

$SESSID_USERNAME = check_session ();

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
   include ("./templates/header.tpl");
   include ("./templates/menu.tpl");
   include ("./templates/password.tpl");
   include ("./templates/footer.tpl");
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
   $pPassword_password_text = $PALANG['pPassword_password_text'];
   
   $fPassword_current = $_POST['fPassword_current'];
   $fPassword = $_POST['fPassword'];
   $fPassword2 = $_POST['fPassword2'];

   $username = $SESSID_USERNAME;
     
  	$result = db_query ("SELECT * FROM admin WHERE username='$username'");
   if ($result['rows'] == 1)
   {
      $row = mysql_fetch_array ($result['result']);
      $salt = preg_split ('/\$/', $row['password']);
      $checked_password = pacrypt ($fPassword_current, $salt[2]);

		$result = db_query ("SELECT * FROM admin WHERE username='$username' AND password='$checked_password'");      
      if ($result['rows'] != 1)
      {
         $error = 1;
         $pPassword_password_current_text = $PALANG['pPassword_password_current_text_error'];
      }
   }
   else
   {
      $error = 1;
      $pPassword_email_text = $PALANG['pPassword_email_text_error']; 
   }

	if (empty ($fPassword) or ($fPassword != $fPassword2))
	{
	   $error = 1;
      $pPassword_password_text = $PALANG['pPassword_password_text_error'];
	}

   if ($error != 1)
   {
      $password = pacrypt ($fPassword);
      $result = db_query ("UPDATE admin SET password='$password',modified=NOW() WHERE username='$username'");
      if ($result['rows'] == 1)
      {
         $tMessage = $PALANG['pPassword_result_succes'];
      }
      else
      {
         $tMessage = $PALANG['pPassword_result_error'];
      }
   }
   
   include ("./templates/header.tpl");
   include ("./templates/menu.tpl");
   include ("./templates/password.tpl");
   include ("./templates/footer.tpl");
}
?>
