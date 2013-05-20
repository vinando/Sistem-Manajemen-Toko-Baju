// JavaScript Document
var outerLayout, middleLayout, innerLayout;
$(document).ready(function () {
    //$( "#tabs" ).tabs({selected:1});
    outerLayout = $('body').layout({
        center__paneSelector:	".outer-center"
    ,	west__paneSelector:		".outer-west"
    ,	west__size:				200
    ,	north__size:			102
    ,	spacing_open:			6 // ALL panes
    ,	spacing_closed:			12 // ALL panes
    ,	north__spacing_open:	0
    ,	south__spacing_open:	0
    ,	center__onresize:		"middleLayout.resizeAll"
    });
    middleLayout = $('div.outer-center').layout({
        center__paneSelector:	".middle-center"
    ,	west__paneSelector:		".middle-west"
    ,	west__size:				100
    ,	spacing_open:			8  // ALL panes
    ,	spacing_closed:			12 // ALL panes
    ,	center__onresize:		"innerLayout.resizeAll"
    });
    innerLayout = $('div.middle-center').layout({
        center__paneSelector:	".inner-center"
    ,	west__paneSelector:		".inner-west"
    ,	west__size:				75
    ,	spacing_open:			8  // ALL panes
    ,	spacing_closed:			8  // ALL panes
    ,	west__spacing_closed:	12
    });
});

function initMenu() {
  $('.isimenu').hide();
  //$('.isimenu:first').show();
  $('.judul').css('cursor', 'pointer');
  $('.judul').click(
    function() {
      if(($(this).next().is('.isimenu')) && ($(this).next().is(':visible'))) {
        $(this).next().slideUp('normal');
        return false;
        }
      if(($(this).next().is('.isimenu')) && (!$(this).next().is(':visible'))) {
        $('.isimenu:visible').slideUp('normal');
        $(this).next().slideDown('normal');
        return false;
        }
      $('.isimenu:visible').slideUp('normal');
      }
    );
  }
$(document).ready(function() {initMenu();});