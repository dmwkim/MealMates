<!DOCTYPE html> 
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
?>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>MealMates!</title>

    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery-ui.css" />
    <link rel="stylesheet" href="style.css" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.js"></script>
<script src="jquery.ui.touch-punch.min.js"></script>
<script src="jquery-ui-timepicker-addon.js"></script>
<script src="https://raw.github.com/briangonzalez/pep.jquery.js/master/js/libs/jquery.pep.js"></script>
<script>
var mealDate;
var mealTime;
var mealPlace;
var mealInvitees = [];
var mealDescription;

function debugData() {
  console.log('mealDate: ' + mealDate);
  console.log('mealTime: ' + mealTime);
  console.log('mealPlace: ' + mealPlace);
  console.log(mealInvitees);
  console.log('mealDescription: ' + mealDescription);
}

function setDate() {
  mealDate = $('#calendar-input').val();
}
function setTime() {
  mealTime = $('#from-time-input').val();
}
function setPlace(place) {
  mealPlace = place;
}
function addInvitee(invitee) {
  mealInvitees.push(invitee);
  console.log(invitee);
}
function removeInvitee(invitee) {
  mealInvitees = jQuery.grep(mealInvitees, function(value) {
    return value != invitee;
  });
}
function setDescription() {
  mealDescription = $('#description-textarea').val();
}

function displayTime() {
  if(mealDate == null){
    $('#display-time').html("Go to the When page to set a Date for the Meal!");
  }
  else if(mealTime == null){
    $('#display-time').html("Go to the When page to set a Time for the Meal!");
  }
  else{
    console.log("Meal time: " + mealTime + " Meal date: " + mealDate);
    $('#display-time').html("at " + mealTime + " on " + mealDate);
  }
}

function displayLocation() {
  if(mealPlace == null){
    $('#display-location').html("Go to the Where page to set a Location for the Meal!");
  }
  else{
    console.log("Meal Place: " + mealPlace);
    $('#display-location').html(mealPlace);
  }
}

function displayInvitees() {
  if(mealInvitees.length == 0){
    $('#display-invitees').html("<b> Go to the Who page to invite people to the Meal! </b>");
  }
  else{
    inviteeString = "";
    for(var i =0; i < mealInvitees.length; i++){
      if(mealInvitees[i] != null){
        inviteeString = inviteeString.concat("<img src='images/" + mealInvitees[i] + ".jpg' width='50px' height='50px'/>");
      }	
    }
    console.log("Invitee String: " + inviteeString);
    $('#display-invitees').html(inviteeString);
  }
}
function postData() {
  $.post('post_meal.php', {
    date: mealDate,
      time: mealTime,
      place: mealPlace,
      invitees: JSON.stringify(mealInvitees),
      description: mealDescription
  }, function(data) {
    console.log(data);
  });
}

$(function() {

  $('#start-over-button').click(function() {
    window.location.href = '/mealmates/';
  });

  /**
   * Listener for invitee buttons.
   */
  $('.invitee-button').click(function() {
    $(this).toggleClass('selected');
    if ($(this).hasClass('selected')) {
      addInvitee($(this).attr('value'));	
    }
    else {
      removeInvitee($(this).attr('value'));
    }
  });

  /*
    $('.invitee-button').click(function() {
    if(!$(this).hasClass('selected')) {
    }
    else {
    }
});
   */

  $('.location-button').click(function() {
    if(!$(this).hasClass('ui-state-disabled')){
      $(this).toggleClass('selected');
      if($(this).hasClass('selected')){
        setPlace($(this).attr('value'));
      }
      else{
        setPlace(null);
      }
      $('.location-button').toggleClass('ui-state-disabled');
      $(this).toggleClass('ui-state-disabled');
    }
  });

  $('.button-confirm').click(function() {
    console.log("Confirm button clicked");
    displayTime();
    displayLocation();
    displayInvitees();
  });



  /**
   * Load welcome screen from PHP backend.
   */
        /*
          $.get('retrieve_meals.php', function(data) {
          console.log(data);
          $.each(data, function(index, entry) {
            console.log(entry);
            var dayContainer = $('<div class="day-container"></div>');
            dayContainer.append($('<h3>' + entry['date']+ '</h3>'))
            var mealContainer = $('<div class="meal-container ui-grid-a"></div>');
            var restaurantButtonContainer = $('<div class="restaurant-button-container ui-block-a"></div>');
            restaurantButtonContainer.append($('<a class="restaurant-button" href="#FamilyDinner" data-role="button" data-inline="true">' + entry['restaurant'] + '</a>'));
            mealContainer.append(restaurantButtonContainer);
            dayContainer.append(mealContainer);
            $('#welcome-content').append(dayContainer);
          });
      });
         */
        /*
        <div class="day-container">
          <h3>Wednesday, May 16</h3>
          <div class="meal-container ui-grid-a">
            <div class="restaurant-button-container ui-block-a">
              <a class="restaurant-button" href="#FamilyDinner" data-role="button" data-inline="true">Cafeteria</a>
            </div>
            <span class="time-window ui-block-b">at <strong>7:00pm</strong></span>
          </div>  
        </div>
         */

  $('#calendar-input').datepicker();
  $('#from-time-input').timepicker({});
  $('#to-time-input').timepicker({});
  $('.am-pm-toggle').click(function() {
    if($(this).text() == 'AM') {
      $(this).html('PM');
    }
    else {
      $(this).html('AM');
    }
  });
  $('#confirm-meal-button').click(function() {
    alert('Success! Everyone you invited will see your meal invitation on your homepage.');
    window.location.href = '/mealmates/';
  });
  $('.cancel-meal').click(function() {
    alert('Your meal has been cancelled.  Everyone you invited will be notified of the cancellation');
    $.post(
      'remove_meal.php',
    {
      meal_id : $(this).attr('value')
    });
    window.location.href = '/mealmates/';

  });
});
    </script>
  </head> 


  <body> 

    <!-- Start of first page: #one -->
    <div data-role="page" id="welcome">

      <div data-role="header">
        <h1>Hi, Justin!</h1>
      </div><!-- /header -->

      <div id="welcome-content" data-role="content" >        
        <h2>Upcoming Meals</h2>
<?php
// Retrieve meals.
$querytemplate = 'SELECT * FROM meals ORDER BY date, start_time;';
$queryreal = sprintf($querytemplate);

$link = mysql_connect('sql.mit.edu', 'dmwkim', '97baystate')
  or die('Could not connect ' . mysql_error());
mysql_select_db('dmwkim+mealmates') or die('Could not select database');

$mealresult = mysql_query($queryreal) or die('Could not select meals table');

while($row = mysql_fetch_assoc($mealresult)) {
?>
        <div class="day-container">
          <h3><?php echo date("m/d/Y", strtotime($row['date']));?></h3>
          <div class="meal-containeri ui-grid-a">
            <div class="restaurant-button-container ui-block-a">
              <a class="restaurant-button" href=

              "#<?php

  $restaurantquery = 'select restaurant_name from restaurant_id_mappings where restaurant_id=\'' . $row['restaurant'] . '\';';
  $restaurantmappingresult = mysql_query($restaurantquery);
  $restaurantrow = mysql_fetch_assoc($restaurantmappingresult);
  //echo $restaurantrow['restaurant_name'];
  echo $row['restaurant'];
  //echo 'Confirm';

?>"

 data-theme="b" data-role="button" data-inline="true"><?php echo $restaurantrow['restaurant_name']; ?></a>
            </div>
            <span class="time-window ui-block-b"><strong>at <?php echo date("g:i a", strtotime($row['start_time'])); ?></strong></span>
          </div>  
        </div>
<?php
}
?>
      </div><!-- /content -->
      <div data-role="footer" data-position="fixed">
        <a id="create-button" href="#When" data-theme="e" data-role="button">Create a New Meal</a>
        <h1>MealMates</h1>
      </div>
    </div><!-- /page one -->

    <div data-role="page" id="When">
      <div data-role="content">
        <div data-role="navbar" data-iconpos="top">
          <ul>
            <li>
            <a class="active-top-button" href="When" data-theme="" data-icon="" class="ui-btn-active" onClick="setDate(); setTime();">
              When
            </a>
            </li>
            <li>
            <a href="#Where" data-theme="" data-icon="" onClick="setDate(); setTime();">
              Where
            </a>
            </li>
            <li>
            <a href="#Who" data-theme="" data-icon="" onClick="setDate(); setTime();">
              Who
            </a>
            </li>
            <li>
            <a class="button-confirm" href="#Confirm" data-theme="" data-icon="" onClick="setDate(); setTime();">
              Confirm
            </a>
            </li>
          </ul>
        </div>
        <h2 id="select-time-header" style="margin-bottom:13px">
          Select a date and time that works for you:
        </h2>
        <h4 id="available-header">
          On which date should the meal take place?
        </h3>
        <div data-role="fieldcontain">
          <fieldset data-role="controlgroup">
            <input id="calendar-input" class="time-number" placeholder="Enter a date" value="" /> 
          </fieldset>
        </div>
        <h4 id="available-header">
          What time do you want the meal to be?
        </h4>
        <div data-role="fieldcontain">
          <fieldset data-role="controlgroup">
            <input id="from-time-input" class="time-number" placeholder="Enter a time" value="" />
          </fieldset>
        </div>
      </div>
  <div>
      <a data-role="button" data-transition="fade" href="#Where" onClick="setDate(); setTime();">
              Save and Continue
      </a>
  </div>
    </div>

    <div data-role="page" id="Where">
      <div data-role="content">
        <div data-role="navbar" data-iconpos="top">
          <ul>
            <li>
            <a href="#When"  data-theme="" data-icon="">
              When
            </a>
            </li>
            <li>
            <a class="active-top-button" href="#Where"  data-theme="" data-icon="" class="ui-btn-active">
              Where
            </a>
            </li>
            <li>
            <a href="#Who"  data-theme="" data-icon="">
              Who
            </a>
            </li>
            <li>
            <a class="button-confirm" href="#Confirm"  data-theme="" data-icon="">
              Confirm
            </a>
            </li>
          </ul>
        </div>
        <!-- <div class="ui-grid-a">
          <div class="ui-block-a">
            <div data-role="fieldcontain">
              <fieldset data-role="controlgroup">
                <input id="textinput11" placeholder="" value="" type="text" />
              </fieldset>
            </div>
          </div>
          <div class="ui-block-b">
            <a data-role="button" data-transition="fade" href="#page6" class="search">
              Search
            </a>
          </div>
  </div> -->
        <div class="ui-grid-a">
          <div class="ui-block-a">
            <div>
              <h3 style="margin-top: 10px;
margin-bottom: 10px;">
                Add locations!
              </h3>
            </div>
          </div>
    <div class="scrollgrid ui-btn-corner-all">
  <table>

       <td class="item">
    <a data-role="button" class="location-button" data-inline="true" data-mini="true" value="flour" onClick="setPlace('flour');">
            <table>
              <tr><td><img src="images/flour.jpg" alt="Flour" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText"> Flour </td></tr>
    </table>
    </a>
       </td>
       <td class="item">
    <a data-role="button" class="location-button" data-inline="true" data-mini="true" value="bertuccis" onClick="setPlace('bertuccis');">
            <table>
              <tr><td><img src="images/bertuccis2.jpg" alt="Bertucci's" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Bertucci</td></tr>
    </table>
    </a>
       </td>
       <td class="item">
    <a data-role="button" class="location-button" data-inline="true" data-mini="true" value="kendall_kitchen" onClick="setPlace('kendall_kitchen');">
            <table>
              <tr><td><img src="images/kendall.jpg" alt="Kendall Kitchen" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Kendalls </td></tr>
    </table>
    </a>
      </td>
      <td class="item">
    <a data-role="button" class="location-button" data-inline="true" data-mini="true" value="unos" onClick="setPlace('unos');">
            <table>
              <tr><td><img src="images/unos.jpg" alt="UNO'S" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">UNO's</td></tr>
    </table>
    </a>
      </td>

      <td class="item">
    <a data-role="button" class="location-button" data-inline="true" data-mini="true" value="au_bon_pain" onclick="setPlace('au_bon_pain');">
            <table>
              <tr><td><img src="images/abp.jpg" alt="Au Bon Pain" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">ABP</td></tr>
    </table>
      </td>
      <td class="item">
    <a data-role="button" class="location-button" data-inline="true" data-mini="true" value="cuchi_cuchi" onClick="setPlace('cuchi_cuchi');">
            <table>
              <tr><td><img src="images/cuchi-cuchi.jpg" alt="Cuchi Cuchi" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Cuchi</td></tr>
    </table>
    </a>
      </td>
      <td class="item">
    <a data-role="button" class="location-button" data-inline="true" data-mini="true" value="friendly_toast" onClick="setPlace('friendly_toast');">
    <table>
              <tr><td><img src="images/friendlytoast.jpg" alt="Friendly Toast" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">F. Toast</td></tr>
    </table>
    </a>
      </td>
      <td class="item">
    <a data-role="button" class="location-button" data-inline="true" data-mini="true" value="capital_grill"  onclick="setPlace('capital_grill');">
            <table>
              <tr><td><img src="images/capitalgrille.jpg" alt="Capital Grill" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Capital G.</td></tr>
    </table>
    </a>
      </td>

      <td class="item">
    <a data-role="button" class="location-button" data-inline="true" data-mini="true" value="tapeo" onclick="setPlace('tapeo');">
            <table>
              <tr><td><img src="images/tapeo.jpg" alt="Tapeo" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Tapeo</td></tr>
    </table>
    </button>
      </td>


  </table>
  </div>
          </div>
        </div>
        <div>
        <a data-role="button" data-transition="fade" href="#Who">
              Save and Continue
        </a>
    </div>
      </div>

    </div>

  </div>

  <div data-role="page" id="Who" class="drag-page">
    <div data-role="content">
      <div data-role="navbar" data-iconpos="top">
        <ul>
          <li>
          <a href="#When" data-theme="" data-icon="">
            When
          </a>
          </li>
          <li>
          <a href="#Where" data-theme="" data-icon="">
            Where
          </a>
          </li>
          <li>
          <a class="active-top-button" href="#Who" data-theme="" data-icon="" class="ui-btn-active">
            Who
          </a>
          </li>
          <li>
          <a class="button-confirm" href="#Confirm" data-theme="" data-icon="">
            Confirm
          </a>
          </li>
        </ul>
      </div>
     <!-- <div class="ui-grid-a">
        <div class="ui-block-a">
          <div data-role="fieldcontain">
            <fieldset data-role="controlgroup">
              <input id="textinput13" placeholder="" value="" type="text" />
            </fieldset>
          </div>
        </div>
        <div class="ui-block-b">
          <a data-role="button" data-transition="fade" href="#page6" class="search">
            Search
          </a>
        </div>
      </div> -->
      <div class="ui-grid-a">
        <div class="ui-block-a">
          <div>
            <h3 style="margin-top: 10px;
margin-bottom: 10px;">
              Invite people!
            </h3>
          </div>
        </div>
        </div>
  <div class="scrollgrid ui-btn-corner-all">
  <table>

       <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="aj_perez">
            <table>
              <tr><td><img src="images/aj_perez.jpg" alt="AJ Perez" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">AJ P.</td></tr>
    </table>
    </a>
       </td>
       <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="akira_monri">
            <table>
              <tr><td><img src="images/akira_monri.jpg" alt="Akira Monri" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Akira M. </td></tr>
    </table>
    </a>
       </td>
       <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="alex_wang">
            <table>
              <tr><td><img src="images/alex_wang.jpg" alt="Alex Wang" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Alex W.</td></tr>
    </table>
    </a>
      </td>
      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="aviv_cukierman">
            <table>
              <tr><td><img src="images/aviv_cukierman.jpg" alt="Aviv Cukierman" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Aviv C.</td></tr>
    </table>
    </a>
      </td>

      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="brian_bell">
            <table>
              <tr><td><img src="images/brian_bell.jpg" alt="Brian Bell" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Brian B.</td></tr>
    </table>
      </td>
      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="chris_haid">
            <table>
              <tr><td><img src="images/chris_haid.jpg" alt="Chris Haid" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Chris H.</td></tr>
    </table>
    </a>
      </td>
      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="david_kim">
    <table>
              <tr><td><img src="images/david_kim.jpg" alt="David Kim" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">David K.</td></tr>
    </table>
    </a>
      </td>
      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="feynman_liang">
            <table>
              <tr><td><img src="images/feynman_liang.jpg" alt="Feynman Liang" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Feyn L.</td></tr>
    </table>
    </a>
      </td>

      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="jake_varley">
            <table>
              <tr><td><img src="images/jake_varley.jpg" alt="Jake Varley" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Jake V. </td></tr>
    </table>
    </button>
      </td>
      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="jimmy_pershken">
            <table>
              <tr><td><img src="images/jimmy_pershken.jpg" alt="Jimmy Pershken" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Jim P.</td></tr>
    </table>
    </a>
      </td>
      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="mark_zuckerberg">
            <table>
              <tr><td><img src="images/mark_zuckerberg.jpg" alt="Mark Zuckerberg" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Mark Z.</td></tr>
    </table>
    </a>
      </td>
      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="mercedes_oliva">
            <table>
              <tr><td><img src="images/mercedes_oliva.jpg" alt="Mercedes Oliva" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Meg O.</td></tr>
    </table>
    </a>
      </td>

      <td class="item">
    <a data-role="button" class="invitee-button" data-inline="true" data-mini="true" value="ron_rosenberg">
            <table class="pep-draggable">
              <tr><td><img src="images/ron_rosenberg.jpg" alt="Ron Rosenberg" height="50px" width="50px"></img></td></tr>
              <tr><td class="draggableText">Ron R.</td></tr>
    </table>
    </a>
      </td>

  </table>
  </div>
        </div>
        <div>
      <a class="button-confirm" data-role="button" data-transition="fade" href="#Confirm">
              Save and Continue
      </a>
  </div>
      </div>
    </div>

  </div>

  <div data-role="page" id="Confirm">
    <div data-role="content">
      <div data-role="navbar" data-iconpos="top">
        <ul>
          <li>
          <a href="#When" data-theme="" data-icon="">
            When
          </a>
          </li>
          <li>
          <a href="#Where" data-theme="" data-icon="">
            Where
          </a>
          </li>
          <li>
          <a href="#Who" data-theme="" data-icon="">
            Who
          </a>
          </li>
          <li>
          <a class="button-confirm" class="active-top-button" href="#Confirm" data-theme="" data-icon="" class="ui-btn-active">
            Confirm
          </a>
          </li>
        </ul>
      </div>

      <div class="ui-grid-a">
        <table align="center" width="100%">
          <tr>
            <td>

              <div class="ui-block-b">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="ui-block-b">
                <h2>
                  Time:
                </h2>
                <div>
      <b id="display-time">
      </b>
                </div>
              </div>
            </td>
            <td>
              <div class="search ui-block-a" style="width:100%;">
                <a id="change" data-role="button" data-transition="fade" href="#When">
                  Change
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="ui-block-b">
                <h2>
                  Location:
                </h2>
                <div>
      <b id="display-location">
      </b>
                </div>
              </div>
            </td>
            <td>
              <div class="search ui-block-a" style="width:100%;">
                <a id="change" data-role="button" data-transition="fade" href="#Where">
                  Change
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="ui-block-b">
                <h2>
                  Invitees:
                </h2>
    <div id="display-invitees">
    </div>
              </div>
            </td>
            <td>
              <div class="search ui-block-a" style="width:100%;">
                <a id="change" data-role="button" data-transition="fade" href="#Who">
                  Change
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="ui-block-b">
                <h2>
                  Description:
                </h2>
                <div>
                  <div data-role="fieldcontain">
                    <fieldset data-role="controlgroup">
                      <textarea id="description-textarea" rows="2" cols="6" placeholder="Optional: Give your meal a description so others know what's going on!" value="" type="text" style="position: relative;
float: left;
width: 330%;
height: 80px;"></textarea>
                    </fieldset>
                  </div>
                </div>
              </div>
            </td>
            <td>
            </td>
          </tr>
        </table>
      </div>
      <div class="ui-grid-a">
        <div class="ui-block-a">
          <a data-role="button" data-transition="fade" href="#welcome" id="start-over-button">
            Start Over
          </a>
        </div>
        <div class="ui-block-b">
          <a id="confirm-meal-button" data-role="button" data-transition="fade" href="#welcome" onClick="setDescription(); postData();">
            Confirm Meal
          </a>
        </div>
      </div>
    </div>
  </div>

<?php
// dynamically generate pages for each meal

$querytemplate = 'SELECT * FROM meals ORDER BY date, start_time;';
$queryreal = sprintf($querytemplate);

/*
$link = mysql_connect('sql.mit.edu', 'dmwkim', '97baystate')
  or die('Could not connect ' . mysql_error());
mysql_select_db('dmwkim+mealmates') or die('Could not select database');
 */
$mealresult = mysql_query($queryreal) or die('Could not select meals table');
/*
while($row = mysql_fetch_assoc($mealresult)) {
?>
  <div data-role="page" id="<?php echo $row['restaurant']; ?>">
  <div data-role="content">
  <div data-role="navbar" data-iconpos="top">
  <ul>
  <li>
  <a href="#welcome" data-theme="" data-icon="">
  Home
  </a>
  </li>
  <li>
  <a class="active-top-button" href="<?php echo $row['restaurant']; ?>2" data-theme="" data-icon="" class="ui-btn-active">
<?php

  //echo $row['restaurant'];
  $restaurantquery = 'select restaurant_name from restaurant_id_mappings where restaurant_id=\'' . $row['restaurant'] . '\';';
  $restaurantmappingresult = mysql_query($restaurantquery);
  $restaurantrow = mysql_fetch_assoc($restaurantmappingresult);
  //echo $restaurantrow['restaurant_name'];

?>
  </a>
    </li>
    </ul>
    </div>
    <h1>
    <?php echo $restaurantrow['restaurant_name']; ?>
  </h1>
    <h2>
    Time
    </h2>
    <div>
    <b>
<?php
  echo date("g:i a", strtotime($row['start_time']));
  echo ' on ';
  echo date("m/d/Y", strtotime($row['date']));
?>

          <br />
        </b>
      </div>
      <h2>
        Location
      </h2>
      <div>
        <b>
          <?php echo $restaurantrow['restaurant_name']; ?>
        </b>
      </div>
      <h2>
        Invitees
      </h2>
      <img src="images/david_kim.jpg" alt="image" width="50px" height="50px"/>
    </div>
  </div>
<?php
}
 */

while($row = mysql_fetch_assoc($mealresult)) {
  $restaurantquery = 'select restaurant_name from restaurant_id_mappings where restaurant_id=\'' . $row['restaurant'] . '\';';
  $restaurantmappingresult = mysql_query($restaurantquery);
  $restaurantrow = mysql_fetch_assoc($restaurantmappingresult);
?>
  <div data-role="page" id="<?php echo $row['restaurant']; ?>">
    <div data-role="content">
      <div data-role="navbar" data-iconpos="top">
        <ul>
          <li>
          <a href="#welcome" data-theme="" data-icon="">
            Home
          </a>
          </li>
          <li>
          <a class="active-top-button" href="<?php echo $row['restaurant']; ?>" data-theme="" data-icon="" class="ui-btn-active">
<?php echo $restaurantrow['restaurant_name']; ?>
          </a>
          </li>
        </ul>
      </div>
      <h1 class="title">
<?php echo $restaurantrow['restaurant_name']; ?>
      </h1>

      <table>
        <tr>
          <td>
            <h2 class="content1">
              Time
            </h2>
          </td>
          <td>
            <div class="content1">
              <b>
<?php
  echo date("g:i a", strtotime($row['start_time'])); 
  echo ' on ';
  echo date("m-d-Y", strtotime($row['date']));
?>

                <br />
              </b>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <h2>
              Location
            </h2>
          </td>
          <td>
            <div>
              <b>
<?php echo $restaurantrow['restaurant_name']; ?>
              </b>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <h2>
              Invitees
            </h2>
          </td>
          <td>
<?php

  $inviteequery = "SELECT inv.invitee as person from invitees inv, meals m WHERE m.meal_id = " . $row['meal_id'] . " and m.meal_id = inv.meal_id;";
  $inviteemappingresult = mysql_query($inviteequery);

  while($inviteerow = mysql_fetch_assoc($inviteemappingresult)) {
?>
          <img src="images/<?php echo $inviteerow['person']; ?>.jpg" alt="image" width="50px" height="50px" />
<?php
  }
?>
           </td>
        </tr>
        <tr>
          <div data-role="fieldcontain">
            <fieldset data-role="controlgroup" data-type="vertical">
              <td>
                <legend>
                  <h2>RSVP:</h2>
                </legend>
              </td>
              <td>
                <input name="radiobuttons1" id="radio1" value="attending" type="radio" />
                <label for="radio1">
                  Attending
                </label>
                <input name="radiobuttons1" id="radio2" value="maybe" type="radio" />
                <label for="radio2">
                  Maybe attending
                </label>
                <input name="radiobuttons1" id="radio3" value="not" type="radio" />
                <label for="radio3">
                  Can't go
                </label>
              </fieldset>
            </td>
          </div>
        </tr>
        <tr>
          <td>
          <a class="cancel-meal" data-role="button" data-transition="fade" href="#" value="<?php echo $row['meal_id']; ?>">
              Cancel Meal
            </a>
          </td>
        </tr>
      </table>
    </div>
  </div>
<?php
}
?>
</body>
</html>
