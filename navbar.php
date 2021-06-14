<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Events</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="records.php">Records</a></li>
        <li><a href="favs.php">Favorites</a></li>
	<li><a href="charts.php">Charts</a></li>
<!--        <li><a href="#">Link2</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>-->
      </ul>
      <form class="navbar-form navbar-left" method="post">
        <div class="form-group">

<div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" name="date1" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>

<div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" name="date2" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>


<!--          <input type="text" class="form-control" placeholder="Search">
          <input type="text" class="form-control" placeholder="Search2">-->
        </div>
        <button type="submit" class="btn btn-default">Search</button>

<div class="form-group">
<div class='input-group date'>
                    <input type='text' class="form-control" name="date3" id="datetimepicker3" / style="display:none">
                   <!-- <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>-->
                </div>
</div>





      </form>



      <ul class="nav navbar-nav navbar-right">
        <li><a id="stats" href="#"></a></li>
<!--        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>-->
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<script>
var i = setInterval(function(){
                                    $.ajax({
                                        type:"GET",
                                        url:"<?=base_url()?>/api.php?stats",
                                        dataType:"json",
                                        success:function(data) {
                                            var html
                                            if (data.new_events > 0) {
                                            html = "<font color=\"red\">New events:</font> "+data.new_events+ " (Unseen)";
                                            } else {
                                            html = "";
                                            }
                                            html = html + " <b>Events</b> Last hour: "+data.per_hour;
                                            html = html + " Today: "+data.per_day;
                                            html = html + " This Week: "+data.per_week;
                                            html = html + " This Month: "+data.per_month;
                                            html = html + " This Year: "+data.per_year;
                                            html = html + " Storage: "+data.disk_usage;
                                            jQuery('#stats').html(html);

                                        },
                                        error:function(xhr,ajaxOptions,thrownError) {
                                        console.log(xhr.status);
                                        console.log(thrownError);
					}
                                    });
                                },1000);

  $(function () {

                $('#datetimepicker1').datetimepicker({
                    format: 'YYYY.MM.DD H:mm:s'
                });
                $('#datetimepicker2').datetimepicker({
                    format: 'YYYY.MM.DD H:mm:s'
                });
               
            });

$("#datetimepicker3").datepicker({
    // The hidden field to receive the date
//    altField: "#dateHidden",
    // The format you want
    altFormat: "yy-mm-dd",
    showOn: "button",
    buttonText: "Select day",
    // The format the user actually sees
    dateFormat: "yy-mm-dd",
    onSelect: function (date) {
        // Your CSS changes, just in case you still need them        
var start_date = date + " 00:00:00";
var end_date = date + " 23:59:59";
console.log("Search between: "+start_date+" and "+end_date);


var url = '<?=$_SERVER["PHP_SELF"]?>';
var form = $('<form action="' + url + '" method="post">' +
  '<input type="text" name="date1" value="' +start_date+ '" />' +
  '<input type="text" name="date2" value="' +end_date+ '" />' +
  '</form>');
$('body').append(form);
form.submit();



    }
});

</script>
