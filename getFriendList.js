/* PostQ - Post-Quantum Secure Messenger */

var friends;

function generateMenu() {
  //get friends - name,userId,symkey
  $.post("getFriendList.php", {username: inputEmail, password: authenticationkey},
  function(data, status){
    //empty the menu
    $('#menu').empty();
    //add AddFriend button to the top
    $('#menu').append('<li id="menuAddnewfriend" class="active"><a href="javascript:showAddNewFriend()">' + ICONS.plus + ' New friend</a></li>');
    $('#menu').append('<li id="menuFriendRequests"><a href="javascript:showFriendRequests()">' + ICONS.users + ' Friend requests</a></li>');
    friends = $.csv.toArrays(data);
    for(var i = 0; i < friends.length; i++) {
      $('#menu').append('<li id="menuMsgs' + friends[i][1] + '"><a href="javascript:showMessages(\'' + friends[i][0] + '\',\'' + friends[i][1] + '\',\'' + friends[i][2] + '\')">' + ICONS.user + ' ' + friends[i][0] + '</a></li>');
    }
    $('#menu').append('<li><a href="javascript:signout()">' + ICONS.logout + ' Logout</a></li>');
  });
}
