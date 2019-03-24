/*!
 * Agency v1.0.x (http://startbootstrap.com/template-overviews/agency)
 * Copyright 2013-2016 Start Bootstrap
 * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap/blob/gh-pages/LICENSE)
 */

// jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

// Highlight the top nav as scrolling occurs
$('body').scrollspy({
    target: '.navbar-fixed-top'
})

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a:not(.dropdown-toggle)').click(function() {
    $('.navbar-toggle:visible').click();
});

$("header").css('height', window.innerHeight+'px')

//YOUTUBE API
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;
function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
           height: '390',
           width: '640',
           videoId: '_b09zlS-RKE',
           playerVars: { 
               'rel': 0
           },
           events: {
               'onReady': onPlayerReady,
               'onStateChange': onPlayerStateChange,
           }
    });
}

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
}

var done = false;
function onPlayerStateChange(event) {
	if (event.data == YT.PlayerState.PLAYING && !done) {
		done = true;
	}
}

$("#learn-more").click(function() {
	$(this).fadeOut('slow');        

	$(".hide-slow").animate({
		opacity: 0
	}, 1000)
	$(".move-up span").fadeOut();

	$( ".move-up" )
		.animate({
		marginTop: "-250",
	}, 1000, function() {
			$("#video").fadeIn('fast')
			player.playVideo();
		}
	);
})

$("#close-video").click(function() {
	closeVideo();
})

function closeVideo() {
	player.stopVideo();
	$("#learn-more").fadeIn('slow');        
	$("#video").fadeOut('fast')
	$(".move-up span").fadeIn();

	$(".hide-slow").animate({
		opacity: 100
	}, 1000)

	$( ".move-up" )
		.animate({
		marginTop: "0",
	}, 1000, function() {
		}
	);

}

$(document).on('change', '#ad', function() {
    window.open($(this).val(), '_blank');
})

button_width = $("#learn-more").outerWidth();
$("#ad").css('width', button_width+'px');
