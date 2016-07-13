var nextVars = 0;

$(document).ready(function(){

ajaxcall();

setInterval(function(){ ajaxcall(); }, 10000);
$(".toggele-btn").click(function(e){
    $(".right-main-wrpr,.left-main-wrpr").toggle();
});
$(".sawaalForYaroTweet").click(function(e){
     e.preventDefault();
     var sawaalText = $.trim($('.sawaalForYaro').val());
    if(sawaalText == "") {
        alert("Please enter your question for Y.A.R.O using #SABKaYARO.");
    } 
    else if(sawaalText.search("#SABKaYaroDemo1") == -1) {
        alert("Please enter hashtag #SABKaYARO.");    
    }   
    else {
       
    var title  = encodeURIComponent(sawaalText);
    var loc = "http://localhost/yaro/";
    var popupWindow = window.open('http://twitter.com/share?url=' + loc + '&text=' + title, 'twitterwindow', 'height=450, width=550, top='+($(window).height()/2 - 225) +', left='+$(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
    
    var pollTimer = window.setInterval(function() {
    if (popupWindow.closed !== false) {
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

            var ans = linkify(dataList[3]);
            function linkify(inputText) {
                var replacedText, replacePattern1, replacePattern2, replacePattern3;

                //URLs starting with http://, https://, or ftp://
                replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
                replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

                //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
                replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
                replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

                //Change email addresses to mailto:: links.
                replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
                replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

                return replacedText;
            }

        	$('.leftAnswer').html(ans);




        	if(dataList[4] != "") {
                var image = "<img class='img-responsive margin-top10' src='"+dataList[4]+"'>";
                $('.leftAnswer').append(image);
        	}
        	nextVars = dataList[5];
        }
    });

}