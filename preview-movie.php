<?php
require_once("config.php");
require_once("header.php");
?>
<script>
var id = <?=(int)$_GET["id"]?>;
var next = 0;
var previous = 0;
function prev() {
  if (previous == null) {
     alert('You are at the most beginning, no more videos left to display...');
  } else {
    showid(previous);
  }
}
function nextas() {
  if (next == null) {
    alert('SORRY! No more records found...');
  } else {
    showid(next);
  }
}

function showid(idas) {
                                        jQuery.ajax
                                        ({
                                            type: "GET",
                                            url: "api.php?getmoviedata="+idas,
                                            dataType: 'json',
                                            success: function (data) {
                                               var details = "Event recorded on camera: "+data.camera+" at: "+data.time;
                                               var debug = "Debug details! Current id: "+id+" Next id: "+data.next_id+" previous id: "+data.previous_id;
                                               $('#details').text(details);
                                               document.title = details;
                                               $('#debug').text(debug);
                                               if (data.comment != "") {
                                                 $('#comment').text(data.comment);
                                               }
                                                 var video = document.getElementById('pav');
                                                 video.src = 'watch.php?file='+data.videourl;
                                                 video.play();
                                               $('#enlarge').attr('href','watch.php?file='+data.videourl);
                                               next = data.next_id;
                                               previous = data.previous_id;
                                            },
                                            error: function () {
                                                console.log("request no. 1 have failed " + url);
                                            }
                                        }).then(function () {
                                            //jQuery('#puslapis_load').hide();
                                        });

}
</script>
<div id="details"></div>
<div id="debug"></div>
<div id="comment"></div>
<video id="pav" width="1000" height="700" controls autoplay>
Your browser does not support the video tag.
</video>
<button onclick="prev()">Previous</button>
<button onclick="nextas()">Next</button>
<a id="enlarge" target="_blank" href="<?=base_url()?>/watch.php?file='+failas+'"><button type="button" style="background-color: #4CAF50; padding: 12px 12px; width: 100%;"></button></a><br>
<button type="button" style="background-color: #f44336; padding: 12px 12px; width: 100%;" onclick="self.close()"></button>
<script>

$(document).ready(function () {
showid(id);
$(document).keydown(function(e){
    if (e.which == 37) {
       prev();
       return false;
    }
   if (e.which == 39) {
       nextas();
       return false;
    }
   if (e.which == 27) {
   window.close();
   return false;
   }
});




});
</script>
