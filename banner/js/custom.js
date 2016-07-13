var nextVars = 0;

$(document).ready(function(){

setInterval(function(){ ajaxcall(); }, 10000);

});

function ajaxcall() {

            $.ajax({
                    url: "load_tweet.php",
                    type: 'POST',
                    data: { next : nextVars},
                    success: function(data, textStatus, xhr) {
                          if($.trim(data) !="") {
                            data = JSON.parse($.trim(data));
                            $('.tweetProfile img').attr('src',data.profile_photo);
                            $('.tweetUserName').html(data.user_screen_name);
                            $('.tweetQuestion').html(data.question);
                            $('.tweetAnswer').html('');
                            setTimeout(function(){ $('.tweetAnswer').html(data.ans); }, 1000);
                            nextVars = data.nextValue;
                          }
                          return false;
                    },
                  error:function(){
                    console.log("Something went wrong.");
                    return false;
                  }
                });

}