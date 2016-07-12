var nextVars = 0;

$(document).ready(function(){

ajaxcall();

setInterval(function(){ ajaxcall(); }, 10000);

$(".sawaalForYaroTweet").click(function(e){
     e.preventDefault();
    if($.trim($('.sawaalForYaro').val()) == "") {
        alert("Please enter apne sawaal for Yaro!");
    } 
    else {

    var title  = encodeURIComponent($('.sawaalForYaro').val());
    var loc = "http://localhost/yaro/";
    //We trigger a new window with the Twitter dialog, in the middle of the page
    var popupWindow = window.open('http://twitter.com/share?url=' + loc + '&text=' + title, 'twitterwindow', 'height=450, width=550, top='+($(window).height()/2 - 225) +', left='+$(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
    
    var pollTimer = window.setInterval(function() {
    if (popupWindow.closed !== false) { // !== is required for compatibility with Opera
        window.clearInterval(pollTimer);
        searchQuestion($('.sawaalForYaro').val());
    }
    }, 200);

    }

});

});

function searchQuestion(tweetText) {

    $.ajax({
                    url: "get_tweet.php",
                    type: 'POST',
                    data: { mode : 'saveTweet',tweetText:tweetText},
                    success: function(data, textStatus, xhr) {
                          if($.trim(data) !="") {
                                /*data = JSON.parse(data);*/
                                alert(data);

                                
                          }
                          return false;
                    },
                  error:function(){
                    alert("Something went wrong.");
                    return false;
                  }
                });

    return false;

}

function ajaxcall() {
$.post("get_tweet.php",
    {
        next:nextVars,
        mode: 'slideTweet'
    },
    function(data, status){
        if(data) {
        	var dataList = JSON.parse(data);

        	$('.leftQuestionProfile').html("@" + dataList[0]);
            $('.leftQuestionProfilePic').attr('src',dataList[1]);
            $('.leftQuestion').html(dataList[2]);
        	$('.leftAnswer').html(dataList[3]);

        	if(dataList[4] != "") {
                var image = "<img class='img-responsive margin-top10' src='"+dataList[4]+"'>";
                $('.leftAnswer').append(image);
        	}
        	nextVars = dataList[5];
        }
    });

}