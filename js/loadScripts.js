$(document).ready(function(){

    function updateTime() {
        var now = new Date();
        $('.currentTime').html(now.toLocaleTimeString());
    }
    updateTime();
    setInterval(updateTime, 1000);
    
    var duration = 0;
    function writeDuration(durationParam) {
        var durationLeft = durationParam;
        var hours = Math.floor(durationLeft / 3600);
        durationLeft -= (hours * 3600);
        var minutes = Math.floor(durationLeft / 60);
        durationLeft -= (minutes * 60);
        var seconds = durationLeft;
        var result = hours.toString().padStart(2,'0') + ":" 
            + minutes.toString().padStart(2,'0') + ":" 
            + seconds.toString().padStart(2,'0');
        return result;
    }
    
    setInterval(function() {
        duration += 1;
        // writeDuration();
        $('.trackDuration').html(writeDuration(duration));
    }, 1000);

    $('div.currentLeg .future').hide();
    $('div.currentLeg .past').hide();
    $('div.futureLeg .current').hide();
    $('div.futureLeg .past').hide();

    $('button').click(function(){

        // get elements by class and pick the first one
        // (there should be only one .currentLeg element)
        var oldLegId = document.getElementsByClassName("currentLeg")[0].id;
        var newLegId = (Number(oldLegId)+1).toString();
        var oldLegSelector = '#' + oldLegId;
        var newLegSelector = '#' + newLegId;

        $(oldLegSelector).removeClass('currentLeg');
        $(oldLegSelector).addClass('pastLeg');
        $(newLegSelector).removeClass('futureLeg');
        $(newLegSelector).addClass('currentLeg');

        $(oldLegSelector + ' .current').hide();
        $(newLegSelector + ' .future').hide();
  
        $(oldLegSelector + ' .past-duration').html(writeDuration(duration));
        duration = 0;
        // alert(writeDuration);
        $('.trackDuration').html(writeDuration(duration));
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
