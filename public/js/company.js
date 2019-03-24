var audio = new Audio('/audio/chime.mp3');

var check_for_requests = function() {
    company_id = window.location.pathname.split('/')[2]
    $.ajax({
        url: '/companies/'+company_id+'/updates', 
        method: 'get',
        success:function(response) {
            result = JSON.parse(response);
            total_requests = result.network_requests + result.company_requests;
            if (isNaN(total_requests) == false){
                $("#requests-count").html('(' + total_requests + ')');
                if(total_requests > 0) {
                    $("#requests-alert").show();
                    audio.play();
                } else {
                    $("#requests-alert").hide();
                }
            }
        }
    })
}

window.setInterval(function() {
    check_for_requests();
}, 450000)
