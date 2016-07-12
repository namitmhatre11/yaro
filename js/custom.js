var nextVars = 0;

$(document).ready(function(){

ajaxcall();

setInterval(function(){ ajaxcall(); }, 10000);

});

function ajaxcall() {
$.post("get_tweet.php",
    {
        next:nextVars
    },
    function(data, status){
        if(data) {
        	var dataList = JSON.parse(data);

        	$('.leftQuestion').html(dataList[2]);
        	$('.leftQuestionProfile').html("@" + dataList[0]);
        	$('.leftQuestionProfilePic').attr('src',dataList[1]);
        	$('.leftAnswer').html(dataList[3]);

        	if(dataList[4] != "") {
        		$('.left-btm-content').css('background-image',"url("+dataList[4]+")");
        		//$('.left-btm-content').css('background-size',"no-repeat");
        	}
        	else {
        		$('.left-btm-content').css('background-image',"url('img/tw-bg.png')");
        		//$('.left-btm-content').css('background-size',"contain;");
        	}
        	console.log(dataList[5]);
        	nextVars = dataList[5];
        }
    });

}