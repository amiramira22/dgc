<?php 

function is_in_array($array, $key, $key_value){
      $within_array = 'no';
      foreach( $array as $k=>$v ){
        if( is_array($v) ){
            $within_array = is_in_array($v, $key, $key_value);
            if( $within_array == 'yes' ){
                break;
            }
        } else {
                if( $v == $key_value && $k == $key ){
                        $within_array = 'yes';
                        break;
                }
        }
      }
      return $within_array;
}

function was_there($lat1, $lon1, $lat2, $lon2) {
    $r = 6371; // Radius of the earth in km
    $dLat = deg2rad1($lat2 - $lat1);  // deg2rad below
    $dLon = deg2rad1($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad1($lat1)) * cos(deg2rad1($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $r * $c; // Distance in km
    if ($d <= 0.5) {
        return true;
    } else {
        return false;
    }
}

function deg2rad1($deg) {
    return $deg * (3.14/ 180);
}