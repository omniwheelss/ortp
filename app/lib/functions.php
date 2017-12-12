<?php
function date_diff_check($Date_Stamp,$Time_Stamp,$GMT_DRIFT){

        $last_date=GetUnixTimestamp($Date_Stamp,$Time_Stamp);
        $gmtdate=GetUTCTimestamp()+($GMT_DRIFT*3600);

        $difference=$gmtdate-$last_date;
        $difference=round(($difference/3600),2);
        $difference=abs($difference);

    return $difference;
}

//-------------------------------------------------------------------------------------------------------------
// Function to get Unix time stamp from ASCII values of a given time and data
//
// Input Parameters:
// $Date - Date in format dd.mm.yyyy
// $Time - Time in format hh:mm:ss
// Output Parameters
// int - Unix timestamp

function GetUnixTimestamp($Date, $Time){
  $day   = substr($Date,0,2);
  $month = substr($Date,3,2);
  $year  = substr($Date,6,4);

  $hour  = substr($Time,0,2);
  $mins  = substr($Time,3,2);
  $secs  = substr($Time,6,2);

  $timestamp = gmmktime($hour,$mins,$secs,$month,$day,$year);
  return $timestamp;
}



//-------------------------------------------------------------------------------------------------------------
// Function to get the current UTC time stamp
// No Input Parameters
// Output Parameters
// int - UTC Unix timestamp

function GetUTCTimestamp (){
        $timestamp=time();
        return $timestamp;
}

//-------------------------------------------------------------------------------------------------------------
// Function to compute geographical distance between a given set of co-ordinates.
// function GetUnixTimestamp($Date, $Time)
// Input Parameters:
// $lat1 - Latitude of Co-ordinate 1
// $lon1 - Longitude of Co-ordinate 1
// $lat2 - Latitude of Co-ordinate 2
// $lon2 - Longitude of Co-ordinate 2
//
// Output Parameters:
// $d - Distance in Kms

function distance($lat1, $lon1, $lat2, $lon2) {
    $iRadiusEarth = 6371; // kms
    $lat1 /= 57.29578;
    $lat2 /= 57.29578;
    $lon1 /= 57.29578;
    $lon2 /= 57.29578;

    $dlat=$lat2-$lat1;
    $dlon=$lon2-$lon1;

    $a = ( sin($dlat/2) * sin($dlat/2) ) + ( cos($lat1) * cos($lat2) ) * ( sin($dlon/2) * sin($dlon/2) );
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $d = $iRadiusEarth * $c;

    // Distance is returned in Kms
    return $d;
}

// ------------------------------------------------------------------------------------------------------------
// Function to check the difference between 2 date/time stamps
//
// Input Parameters:
// $date_1 - First Date in format dd.mm.yyyy
// $time_1 - First Time in format hh:mm:ss
// $date_2 - Second Date in format dd.mm.yyyy
// $time_2 - Second Time in format hh:mm:ss
//
// Return values
// $time_difference - Difference in time in seconds


function timediff($date_1,$time_1,$date_2,$time_2){
        $x=GetUnixTimestamp($date_1,$time_1);
        $y=GetUnixTimestamp($date_2,$time_2);

        $time_difference=$y-$x;
        return $time_difference;
}


// ------------------------------------------------------------------------------------------------------------
// Function to convert date display based on account defaults based for an IMEI
//
// Input Parameters:
// IMEI  - IMEI for which date format has to be changed
// $Date - Date in format dd.mm.yyyy
//
// Output Parameters
// Date in either ddmmyyyy or mmddyyyy format with the defined date separator

function convert_date_display($date)
{
  global $DATE_FORMAT;
  global $DATE_SEPERATOR;

  if( $DATE_FORMAT == 1 )
  {
    $dd = substr($date,0,2);
    $mm = substr($date,3,2);
    $yy = substr($date,6,4);
    return "$mm$DATE_SEPERATOR$dd$DATE_SEPERATOR$yy";
  }
  else
  {
    $dd = substr($date,0,2);
    $mm = substr($date,3,2);
    $yy = substr($date,6,4);
    return "$dd$DATE_SEPERATOR$mm$DATE_SEPERATOR$yy";
  }
}



function get_time_difference( $start, $end ){

    $uts['start']      =    strtotime( $start );
    $uts['end']        =    strtotime( $end );
    if( $uts['start']!==-1 && $uts['end']!==-1 )
    {
        if( $uts['end'] >= $uts['start'] )
        {
            $diff    =    $uts['end'] - $uts['start'];
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            $diff    =    intval( $diff );            
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
        }
        else
        {
            trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
        }
    }
    else
    {
        trigger_error( "Invalid date/time data detected", E_USER_WARNING );
    }
    return( false );
}

?>