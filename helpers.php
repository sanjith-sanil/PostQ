<?php
/* PostQ - Post-Quantum Secure Messenger */

function loginhelper($conn, $username, $password) {
  // prepare, bind and execute
  $stmt = $conn->prepare("SELECT password, privatekey FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  if ($stmt->errno)
    return "Error during the execution of the SQL query";

  //get the result
  $stmt->bind_result($db_password, $privatekey);

  if(!$stmt->fetch()) { //username not found in users!
    //check if username is in unverified_users
    $stmt = $conn->prepare("SELECT username FROM unverified_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->errno)
      return "Error during the execution of the SQL query";
    $stmt->bind_result($db_username);

    if($stmt->fetch()){ //username found in unverified_users!
      return "Please verify your e-mail before sign in."; //username unverified
    } else {
      return "Incorrect credentials"; //username does not found
    }
  }

  if(password_verify($password,$db_password)) {
    return "1".$privatekey;
    $stmt->close();
  } else {
    return "Incorrect credentials";
    $stmt->close();
  }
}

function userExists($conn, $username) {
  // prepare, bind and execute
  $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  if ($stmt->errno)
    return "Error during the execution of the SQL query";

  //get the result
  $stmt->bind_result($db_username);

  if(!$stmt->fetch())
    return False; //user does not exist

  return True; //user exists
}

function getUserId($conn, $username) {
  // prepare, bind and execute
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  if ($stmt->errno)
    return "Error during the execution of the SQL query";

  //get the result
  $stmt->bind_result($userid);

  if(!$stmt->fetch())
    return "Error - user does not exist";

  return $userid; //user exists
}

// Process verification codes and clean unverified_users table
function verifyhelper($conn, $code) {
  $ret_message="";
  $now = time();

  // check if code exists
  $stmt = $conn->prepare("SELECT UNIX_TIMESTAMP(expires) FROM unverified_users WHERE registrationcode=?");
  $stmt->bind_param("s", $code);
  $stmt->execute();
  if($stmt->errno) {
    return("Error during the execution of the SQL query");
  }
  $stmt->bind_result($db_expires);

  if(!$stmt->fetch()) { //code does not exist
	$ret_message .= "Invalid code requested";
  } else { //code exists
    if($now > $db_expires) { //expired code
     $ret_message.="Code expired. Please register again\n";
    } else {  //valid code found!
      $stmt->close();
      $stmt = $conn->prepare("INSERT INTO users (username, password, privatekey, publickey) SELECT username, password, privatekey, publickey FROM unverified_users WHERE registrationcode=?");
      $stmt->bind_param("s", $code);
      $stmt->execute();
      if($stmt->errno) {
        return("Error during the execution of the SQL query");
      }
      $ret_message.="Thanks for registering. Now you can sign in.";
    }
  }

  //delete processed entry and clean up table from expired codes
  $stmt = $conn->prepare("DELETE FROM unverified_users WHERE registrationcode=? OR expires<FROM_UNIXTIME(?)");
  $stmt->bind_param("ss", $code, $now);
  $stmt->execute();
  if($stmt->errno) {
   //weird things can happen if execution reaches here and user verify same code, because it will be generated duplicated entry in table users
   //maybe create alert about this situation: alertWebmaster("check for duplicated usernames in table users");
   $ret_message.="\n  Error flushing code";
  }

  return $ret_message;
}
?>
