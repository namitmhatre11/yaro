var nextVars = 0;

$(document).ready(function(){

ajaxcall();

setInterval(function(){ ajaxcall(); }, 10000);

$(".sawaalForYaroTweet").click(function(e){
     e.preventDefault();
     var sawaalText = $.trim($('.sawaalForYaro').val());
    if(sawaalText == "") {
        alert("Please enter apne sawaal for Yaro!");
    } 
    else {

    sawaalText = "#SABKaYaroDemo1 " + sawaalText;

    var title  = encodeURIComponent(sawaalText);
    var loc = "http://localhost/yaro/";
    //We trigger a new window with the Twitter dialog, in the middle of the page
    var popupWindow = window.open('http://twitter.com/share?url=' + loc + '&text=' + title, 'twitterwindow', 'height=450, width=550, top='+($(window).height()/2 - 225) +', left='+$(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
    
    var pollTimer = window.setInterval(function() {
    if (popupWindow.closed !== false) { // !== is required for compatibility with Opera
        window.clearInterval(pollTimer);
        searchQuestion(sawaalText);
    }
    }, 200);

    }

});

});

function searchQuestion(tweetText) {
    var that = $('.sawaalForYaroTweet');
    that.html("<i class='fa fa-circle-o-notch fa-spin'></i> Processing Sawal...");
    that.attr("disabled", true);
    $.ajax({
                    url: "get_tweet.php",
                    type: 'POST',
                    data: { mode : 'saveTweet',tweetText:tweetText},
                    success: function(data, textStatus, xhr) {
                          if($.trim(data) !="") {
                                data = JSON.parse($.trim(data));
                                
                                if(data.status == true) {
                                    $('.yaraTweetAnswer').html(data.dataResponse[1]); 
                                }
                                else {
                                    alert(data.message);    
                                }    
                                that.html("Tweet"); 
                                that.attr("disabled", false);                         
                          }
                          return false;
                    },
                  error:function(){
                    console.log("Something went wrong.");
                    that.html("Tweet");
                    that.attr("disabled", false);
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