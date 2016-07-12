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

        	$('.leftQuestionProfile').html("@" + dataList[0]);
            $('.leftQuestionProfilePic').attr('src',dataList[1]);
            $('.leftQuestion').html(dataList[2]);
        	$('.leftAnswer').html(dataList[3]);

        	if(dataList[4] != "") {
                var image = "<img src='"+dataList[4]+"'>";
                $('.leftAnswer').append(image);
        	}
        	nextVars = dataList[5];
        }
    });

}