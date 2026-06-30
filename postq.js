/* PostQ - Post-Quantum Secure Messenger */
 
function pageLoaded() {
  if($('#messages').length) $('#messages').scrollTo("max"); //if messages, scroll to bottom

  //Check for localStorage and login user
  if (localStorage.getItem("local_username") !== null) {
	  signin_from_localStorage();
  }
}

function send(text) {
  msgId++;
  var msg = $('#newmsg').val();
  if(typeof text === 'string')
    msg=text;

  var msgWithId = msgId.toString() + ";" + msg;
  $('#newmsg').val(''); //cleare the message box
  
  var nonce = secureRandom(8);
  var encMsg = AESencryptCTR(msgWithId,msgSymKey,nonce);
  var hexNonce = ByteArray_2_HexString(nonce);
  $.post("sendMsg.php", { username: inputEmail, password: authenticationkey,  user2Id: user2Id, msg: encMsg, nonce: hexNonce}, 
  function(data, status){
    if(data == 1) { //if success, display message
      $("#alertMessages").hide(); //hide the alert
      $('#messages').append('<div class="msgFromMe">' + msg + '</div>');
      $('#messages').scrollTo("max",500);
    } else {
      displayAlert("#alertMessages","danger","Sending message failed. " + data);
    }
  });
}
