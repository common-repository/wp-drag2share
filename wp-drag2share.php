<?php
/*
Plugin Name: WP-Drag2Share
Version: 1.0.0
Plugin URI: http://craftyman.net/wp-drag2share-plugin-para-wordpress
Description: WP-Drag2Share is a simple tool to share a post easily by drag and drop, this plugin supports social networks like Twitter, Delicious and Facebook.
Author: Cesar Mancilla
Author URI: http://craftyman.net
*/

function drag2shareHeader() 
{
$path = get_option('siteurl') . '/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/';
echo '<link rel="stylesheet" type="text/css" href="'.$path.'dragToShare.css">'."\n";
echo '<script type="text/javascript" src="'.$path.'js/jquery-1.3.2.min.js"></script>'."\n";
echo '<script type="text/javascript" src="'.$path . 'js/jquery-ui-1.7.2.custom.min.js"></script>'."\n";
?>
<script type="text/javascript">
  $(function() {
    
    //cache selector
    var images = $("#content img"),
      title = $("title").text() || document.title;
    
    //make images draggable
    images.draggable({
      //create draggable helper
      helper: function() {
        return $("<div>").attr("id", "helper").html("<span>Drag to Share this Post</span><img id='thumb' src='" + $(this).attr("src") + "'>").appendTo("body");
      },
      cursor: "pointer",
      cursorAt: { left: -10, top: 20 },
      zIndex: 99999,
      //show overlay and targets
      start: function() {
        $("<div>").attr("id", "overlay").css("opacity", 0.7).appendTo("body");
        $("#tip").remove();
        $(this).unbind("mouseenter");
        $("#targets").css("left", ($("body").width() / 2) - $("#targets").width() / 2).slideDown();
      },
      //remove targets and overlay
      stop: function() {
        $("#targets").slideUp();
        $(".share", "#targets").remove();
        $("#overlay").remove();
        $(this).bind("mouseenter", createTip);
      }
    });
    
    //make targets droppable
    $("#targets li").droppable({
      tolerance: "pointer",
      //show info when over target
      over: function() {
                  
                //$("#"+$(this).attr("id")).css({"background":"#fff url(iconSprite.png)","-moz-box-shadow":"0px 0px 15px 5px #000;"});

        $(".share", "#targets").remove();
        $("<span>").addClass("share").text("Share on " + $(this).attr("id")).addClass("active").appendTo($(this)).fadeIn();

      },out: function(){
                    $("#"+$(this).attr("id")).css({"border":"0px solid #fff"});
              },
      drop: function() {
        var id = $(this).attr("id"),
          currentUrl = window.location.href,
          baseUrl = $(this).find("a").attr("href");

        if (id.indexOf("twitter") != -1) {
			window.open(baseUrl + "/home?status=" + title + ": " + currentUrl, '_blank');
        } else if (id.indexOf("delicious") != -1) {
			window.open(baseUrl + "/save?url=" + currentUrl + "&title=" + title, '_blank');
        } else if (id.indexOf("facebook") != -1) {
			window.open(baseUrl + "/sharer.php?u=" + currentUrl + "&t=" + title, '_blank');          
        }
      }		  
    });
  
    var createTip = function(e) {
      //create tool tip if it doesn't exist
      ($("#tip").length === 0) ? $("<div>").html("<span>Drag this image to share the page<\/span><span class='arrow'><\/span>").attr("id", "tip").css({ left:e.pageX + 30, top:e.pageY - 16 }).appendTo("body").fadeIn(500) : null;
    };
    
    images.bind("mouseenter", createTip);
    
    images.mousemove(function(e) {
    
      //move tooltip
      $("#tip").css({ left:e.pageX + 30, top:e.pageY - 16 });
    });
  
    images.mouseleave(function() {
    
      //remove tooltip
      $("#tip").remove();
    });
  });
</script>
<ul id="targets">
<li id="twitter"><a href="http://twitter.com"><!-- --></a></li>
<li id="delicious"><a href="http://delicious.com"><!-- --></a></li>
<li id="facebook"><a href="http://www.facebook.com"><!-- --></a></li>
</ul>
<?php
}
add_action('wp_footer', 'drag2shareHeader');
?>