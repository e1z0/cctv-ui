<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- datetime picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css"> -->

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


<link rel="stylesheet" href="css/style.css">
 <title>Videos</title>
 <script>

function print_r(o) {
  return JSON.stringify(o,null,'\t').replace(/\n/g,'<br>').replace(/\t/g,'&nbsp;&nbsp;&nbsp;'); 
}
var isIPadPro = /Macintosh/.test(navigator.userAgent) && 'ontouchend' in document;
if (isIPadPro == false) {
  $(function() {
    $( document ).tooltip({
      items: "img, [image], [video]",
      show: { delay: 1000 },
      content: function() {
        var element = $( this );
        if ( element.is( "[image]" ) ) {
          var text = element.attr("image");
          return "<img class='photo' alt='" + text +
            "' src='event.php?file=" +
            text + "'>";
        }
        if (element.is("[video]")) {
          var text = element.attr("video");
          return "<video class='video' src='watch.php?file=" + text +
            "' type='video/mp4' width='800' height='600' autoplay>Your browser does not support the video tag.</video>";
        }
      }
    });
  });
} else {

$(document).bind('click tap', ".preview", function(event) {
var target = $( event.target )
if (target.is("[video]")||target.is("[image]")) {
$(this).tooltip({ items: "[image], [video]",
      content: function() {
        var element = $( this );
        if ( element.is( "[image]" ) ) {
          var text = element.attr("image");
          return "<img class='photo' alt='" + text +
            "' src='event.php?file=" +
            text + "'>";
        }
        if (element.is("[video]")) {
          var text = element.attr("video");
          return "<video class='video' src='watch.php?file=" + text +
            "' type='video/mp4' width='800' height='600' autoplay controls>Your browser does not support the video tag.</video>";
        }
      }
});
//$(this).tooltip("open");
return false;
}
});
}

$(document).ready(function () {
if (isIPadPro == false) {
            $("body").delegate("a#OpenDialog","click",function () {
                var element = $( this );
                var failas = element.attr("video");
                var id = element.attr("data-id");
                var count = $('#view'+id).attr('counter');
                count++;
                $('#view'+id).attr('counter',count);
                $('#view'+id).html("<font color='grey'>Viewed "+count+" times</font>");
                var w = window.open("<?=base_url()?>/preview-movie.php?id="+id, "popupWindow", "width=1024, height=798, scrollbars=yes");
            });
            $("body").delegate("a#OpenDialogPic","click",function () {
                var element = $( this );
                var failas = element.attr("image");
                var id = element.attr("data-id");
                var count = $('#view'+id).attr('counter');
                count++;
                $('#view'+id).attr('counter',count);
                $('#view'+id).html("<font color='grey'>Viewed "+count+" times</font>");
                var w = window.open("<?=base_url()?>/preview-pic.php?id="+id, "popupWindow", "width=1044, height=798, scrollbars=yes");
            });

}

 $("body").delegate(".fav","click",function () {
   var element = $(this);
   var dataid = element.attr("data-id");
   var type = element.attr("type");
   var descr = prompt("Enter description", "big and reasonable description");
   if (descr != null) {
   $(this).removeClass("fav cursor-pointer glyphicon glyphicon-plus");
    $.ajax({
                                        type:"POST",
                                        url:"<?=base_url()?>/api.php?addfave="+dataid+"&type="+type,
                                        data: {data:descr},
                                        dataType:"json",
                                        success:function(data) {
                                            if (data.status > 0 ) {
                                            alert("great success!");
                                            }

                                        },
                                        error:function(xhr,ajaxOptions,thrownError) {
                                        }
                                    });
}
});


        }); /// end of the ends
  </script>
</head>
<body>
