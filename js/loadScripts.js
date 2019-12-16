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
        var result = hours.toString().padStart(2,'0') + ":" 
            + minutes.toString().padStart(2,'0') + ":" 
            + seconds.toString().padStart(2,'0');
        // alert(result);
        return result;
        // var hours = Math.floor((duration % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        // var minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
        // var seconds = Math.floor((duration % (1000 * 60)) / 1000);
        // $('.trackDuration').html(hours.toString().padStart(2,'0') + ":" + minutes.toString().padStart(2,'0') + ":" + seconds.toString().padStart(2,'0'));
    }
    
    setInterval(function() {
        duration += 1;
        // writeDuration();
        $('.trackDuration').html(writeDuration());
    }, 1000);

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
        $(newLegSelector + ' .future').hide();
  
        $(oldLegSelector + ' .past-duration').html(writeDuration());
        duration = 0;
        // alert(writeDuration);
        $('.trackDuration').html(writeDuration());
        var now = new Date();
        $(oldLegSelector + ' .past-timestamp').html(now.toLocaleTimeString());

        $(oldLegSelector +  ' .past').show();
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
