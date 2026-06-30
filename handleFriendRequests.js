/* PostQ - Post-Quantum Secure Messenger */

var requests;
var symkeyrequests;

function handleFriendRequests() {

  //get requests to change symkeys
  $.post("getSymkeyRequests.php", { username: inputEmail, password: authenticationkey } ,
    function(data, status){
      symkeyrequests = $.csv.toArrays(data);
      $('#symkeyrequestsouter').empty(); //clear previous requests
      if(symkeyrequests.length > 0){
        $('#symkeyrequestsouter').append(ICONS.info + '&nbsp;&nbsp;Secret code changing request from:<br/>');
      }
      for(var i = 0; i < symkeyrequests.length; i++) {
        $('#symkeyrequestsouter').append('<div> \
           <a href="javascript:acceptSymkeyRequest(' + i.toString() + ')" title="Delete all my messages and accept new secret code">' + ICONS.check + '</a>&nbsp;&nbsp;' +
           symkeyrequests[i][2] +  '</div>');
      }
      if(symkeyrequests.length > 0){
        $('#symkeyrequestsouter').append('<hr/>');
      }
    }
  );

  //get new requests - user1,user2,symkey
  $.post("getNewRequests.php", { username: inputEmail, password: authenticationkey } ,
  function(data, status){
    requests = $.csv.toArrays(data);
    $('#friendrequestsouter').empty(); //clear previous requests
    if (requests.length > 0 ){
      $('#friendrequestsouter').append('Friend request from:<br/>');
    }
    for(var i = 0; i < requests.length; i++) {
      $('#friendrequestsouter').append('<div> \
         <a href="javascript:acceptRequest(' + i.toString() + ')">' + ICONS.check + '</a>&nbsp;&nbsp; \
         <a href="javascript:rejectRequest(' + i.toString() + ')">' + ICONS.x + '</a> &nbsp;&nbsp;' +
         requests[i][2] +  '</div>');
    }
  });
}

function acceptRequest(requestID) {
  var i = parseInt(requestID);
  //NTRU decrypt
  var plainSymKey = NTRUDecapsulate(requests[i][3], privatekey);
  //AES encrypt the symkey
  var AESSymKey = AESencryptCBC_arr(plainSymKey, decryptionkey);
  //send the symkey back, delete the requests
  $.post("acceptRequest.php", { username: inputEmail, password: authenticationkey, friendId: requests[i][1], symkeyforme: AESSymKey },
  function(data, status){
      if(data == "1") { //success
        displayAlert("#alertFriendRequests","success","Friend added successfully!");
        generateMenu();
        handleFriendRequests();
      } else {
        displayAlert("#alertFriendRequests","danger",data);
      }
    }
  );
}

function rejectRequest(requestID) {
  var i = parseInt(requestID);
  //delete request
  $.post("rejectRequest.php", { username: inputEmail, password: authenticationkey, friendId: requests[i][1] },
    function(data, status){
      if(data == "1") { //success
        displayAlert("#alertFriendRequests","success","Friend request rejected!");
        generateMenu();
        handleFriendRequests();
      } else {
        displayAlert("#alertFriendRequests","danger",data);
      }
    }
  );
}

function acceptSymkeyRequest(requestID) {
  var i = parseInt(requestID);
  //NTRU decrypt
  var plainSymKey = NTRUDecapsulate(symkeyrequests[i][3], privatekey);
  //AES encrypt the symkey
  var AESSymKey = AESencryptCBC_arr(plainSymKey, decryptionkey);
  //send the symkey back, delete the requests
  $.post("acceptChangeSymkey.php", { username: inputEmail, password: authenticationkey, friendId: symkeyrequests[i][1], symkeyforme: AESSymKey },
    function(data, status){
      if(data == "1") { //success
        displayAlert("#alertFriendRequests","success","New secret shared!");
        generateMenu();
        handleFriendRequests();
      } else {
        displayAlert("#alertFriendRequests","danger",data);
      }
    }
  );
}
