$(document).ready(function(){

    function updateTime() {
        var now = new Date();
        $('.currentTime').html(now.toLocaleTimeString());
    }
    updateTime();
    setInterval(updateTime, 1000);
    
    var duration = 0;
    function writeDuration() {
        var durationLeft = duration;
        var hours = Math.floor(durationLeft / 3600);
        durationLeft -= (hours * 3600);
        var minutes = Math.floor(durationLeft / 60);
        durationLeft -= (minutes * 60);
        var seconds = durationLeft;
        // var hours = Math.floor((duration % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        // var minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
        // var seconds = Math.floor((duration % (1000 * 60)) / 1000);
        $('.trackDuration').html(hours.toString().padStart(2,'0') + ":" + minutes.toString().padStart(2,'0') + ":" + seconds.toString().padStart(2,'0'));
    }
    
    setInterval(function() {
        duration += 1;
        writeDuration();
    }, 1000);
    // setInterval(trackDuration, 1000);
    // setInterval(trackDuration,1);

    // var currentTime = setInterval(currentDate, 1000);

    // function currentDate() {
    //     var now = new Date();
    //     document.getElementById("datetime").innerHTML = now.toLocaleTimeString();
    // }

    // currentDate();

    $('div.currentLeg .future').hide();
    $('div.currentLeg .past').hide();
    $('div.futureLeg .current').hide();
    $('div.futureLeg .past').hide();

    $('button').click(function(){

        // get elements by class and pick the first one
        var oldLegId = document.getElementsByClassName("currentLeg")[0].id;
        // var oldLegNum = Number(oldLeg);
        // alert(oldLegNum);
        var newLegId = (Number(oldLegId)+1).toString();
        // alert(nextLeg);
        var oldLegSelector = '#' + oldLegId;
        var newLegSelector = '#' + newLegId;
        // alert(oldLegSelector);

        $(oldLegSelector).removeClass('currentLeg');
        $(oldLegSelector).addClass('pastLeg');
        $(newLegSelector).removeClass('futureLeg');
        $(newLegSelector).addClass('currentLeg');

        $(oldLegSelector + ' .current').hide();
        $(oldLegSelector +  ' .past').show();

        duration = 0;
        writeDuration();

        $(newLegSelector + ' .future').hide();
        $(newLegSelector + ' .current').show();

        // duration = 0;


    })
});

// $(document).ready(function(){
//     $("button").click(function(){
//       $("h1, h2, p").addClass("blue");
//       $("div").addClass("important");
//     });
//   });
