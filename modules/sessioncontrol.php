<?php

/**
 * This function must first check where are we
 *
 * If we are on index and we are already logged it must redirect
 * to the panel, if not it must check for login errors, login info or logouts
 *
 * If we are on panel it must check that we are already logged, if not
 * it must redirect us to the index and show an error
 */
function checkSession(){
  //Start session
  session_start();
  //phpinfo() provide information bellow
  $urlFile = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
  
  if($urlFile === "index.php"){
   
    if (isset($_SESSION["email"])) {
      header("Location:./panel.php");
    }else {
// Check for session error
if ($alert = checkLoginError()) return $alert;

// Check for info session variable
if ($alert = checkLoginInfo()) return $alert;

// Check for logout
if ($alert = checkLogout()) return $alert;

    }
  }else {
    if(!isset($_SESSION["email"])) {
      $_SESSION["loginError"] = "You don't have permission to enter the panel. Please Login.";
      header("Location:./index.php");
    }
  }
}

/**
 * This function must unset all session and cookies variables
 * and also destroy the session itself
 */
function destroySession()
{
//Start session
session_start();

//Eliminate all variables stored in global SESSION 
unset($_SESSION);

//Delete cookies
destroySessionCookie();

//Destroy the session
session_destroy();

//Redirect to login page
header("Location:../index.php?logout=true");
}

/**
 * This function must get input form values and check them
 * If user is correct we must redirect user to the private area
 */
function authUser()
{
  // Start session
   session_start();

  //Input values
  $email = $_POST["email"];
  $pass = $_POST["password"];
  
  if(checkUser($email, $pass)){
    //save email in a session variable
    $email = $_SESSION["email"];   
    //After checked password and email by checkUser() function, we redirect to panel
    header("Location:../panel.php");
  }else {
    $_SESSION["loginError"] = "Wrong email or password!";
    header("Location:../index.php");
  }
}

/**
 * This function must emulate a database user search and return
 * true in case email and password matches
 */
function checkUser(string $email, string $pass) {
      $dbemail = "walber.melo@hotmail.com";
      $dbpass = "123456";

      // Password must be encrypted 
      $dbpassEnc = password_hash($dbpass, PASSWORD_DEFAULT);
      
{
  if($email === $dbemail && password_verify($pass,$dbpassEnc)){
return true;    
    } else {
      return false;
    }
}
}

/**
 * This function is used to delete session Cookie
 */
function destroySessionCookie()
{
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
  );
}
  
}

/**
 * This function is used to check for login errors
 */
function checkLoginError()
{
  if(isset($_SESSION["loginError"])) {
    $errorMsg = $_SESSION["loginError"];
    unset($_SESSION["loginError"]);
    return ["type" => "danger", "text" => $errorMsg];
  }
}

/**
 * This function is used to check for login information
 */
function checkLoginInfo()
{
  if(isset($_SESSION["loginInfo"])) {
    $infoMsg = $_SESSION["loginInfo"];
    unset($_SESSION["loginInfo"]);
    return ["type" => "primary", "text" => $infoMsg];
  }
}


/**
 * This function is used to check for logout
 */
function checkLogout()
{
  if (isset($_GET["logout"]) && !isset($_SESSION["email"])) return ["type" => "primary", "text" => "Logout succesful"];
}
