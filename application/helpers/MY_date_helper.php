<?php

function get_simple_date_ranges() {
    $CI = &get_instance();
    $CI->load->language('reports');
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
    $six_days_ago = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")));
    $start_of_this_month = date('Y-m-d', mktime(0, 0, 0, date("m"), 1, date("Y")));
    $end_of_this_month = date('Y-m-d', strtotime('-1 second', strtotime('+1 month', strtotime(date('m') . '/01/' . date('Y') . ' 00:00:00'))));
    $start_of_last_month = date('Y-m-d', mktime(0, 0, 0, date("m") - 1, 1, date("Y")));
    $end_of_last_month = date('Y-m-d', strtotime('-1 second', strtotime('+1 month', strtotime((date('m') - 1) . '/01/' . date('Y') . ' 00:00:00'))));
    $start_of_this_year = date('Y-m-d', mktime(0, 0, 0, 1, 1, date("Y")));
    $end_of_this_year = date('Y-m-d', mktime(0, 0, 0, 12, 31, date("Y")));
    $start_of_last_year = date('Y-m-d', mktime(0, 0, 0, 1, 1, date("Y") - 1));
    $end_of_last_year = date('Y-m-d', mktime(0, 0, 0, 12, 31, date("Y") - 1));
    $start_of_time = date('Y-m-d', 0);

    return array('' => 'Select range', $today . '/' . $today => $CI->lang->line('reports_today'), $yesterday . '/' . $yesterday => $CI->lang->line('reports_yesterday'), $six_days_ago . '/' . $today => $CI->lang->line('reports_last_7'), $start_of_this_month . '/' . $end_of_this_month => $CI->lang->line('reports_this_month'), $start_of_last_month . '/' . $end_of_last_month => $CI->lang->line('reports_last_month'), $start_of_this_year . '/' . $end_of_this_year => $CI->lang->line('reports_this_year'), $start_of_last_year . '/' . $end_of_last_year => $CI->lang->line('reports_last_year'), $start_of_time . '/' . $today => $CI->lang->line('reports_all_time'),);
}
function getStartAndEndDate($week, $year) {
    // Adding leading zeros for weeks 1 - 9.
    $date_string = $year . 'W' . sprintf('%02d', $week);
    $return[0] = date('Y-n-j', strtotime($date_string));
    $return[1] = date('Y-n-j', strtotime($date_string . '7'));
    return $return;
}

function time_to_millisecondes($time) {
    $arr_time = explode(":", $time);
    return ($arr_time[2] + $arr_time[1] * 60 + $arr_time[0] * 60 * 60) * 1000;
}

function millisecondes_to_time($micro) {
    $micro1 = $micro / 1000;
    $hours = intval($micro1 / 3600);
    $minutes = intval(($micro1 - ($hours * 3600)) / 60);
    $seconde = ($micro1 - ($hours * 3600)) - ($minutes * 60);
    return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconde, 2, '0', STR_PAD_LEFT);
}

function split_date($date) {

    $y = substr($date, 0, 4);
    $m = substr($date, 4, 2);
    $d = substr($date, 6, 2);
    return $y . '-' . $m . '-' . $d;
}

function format_date($date) {
    if ($date != '' && $date != '0000-00-00') {
        $d = explode('-', $date);

        $m = Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

        return $m[$d[1] - 1] . ' ' . $d[2] . ', ' . $d[0];
    } else {
        return false;
    }
}

function reverse_format($date) {
    if (empty($date)) {
        return;
    }

    $d = explode('-', $date);

    return "{$d[2]}/{$d[1]}/{$d[0]}";
}

function std_format($date) {
    if (empty($date)) {
        return;
    }

    $d = explode('/', $date);

    return "{$d[2]}-{$d[1]}-{$d[0]}";
}

function format_ymd($date) {
    if (empty($date) || $date == '00-00-0000') {
        return '';
    } else {
        $d = explode('-', $date);
        return $d[2] . '-' . $d[0] . '-' . $d[1];
    }
}

function format_mdy($date) {
    if (empty($date) || $date == '0000-00-00') {
        return '';
    } else {
        return date('m-d-Y', strtotime($date));
    }
}

function datediff($date1, $date2) {
    if ($date1 > $date2)
        return datediff($date2, $date1);
    $first = DateTime::createFromFormat('m/d/Y', $date1);
    $second = DateTime::createFromFormat('m/d/Y', $date2);
    return floor($first->diff($second)->days / 7);
}

function format_week($date) {
    if (empty($date) || $date == '0000-00-00') {
        return '';
    } else {
        return date('o', strtotime($date)) . '-W' . date('W', strtotime($date));
    }
}

function format_month($date) {

    if (empty($date) || $date == '0000-00-00') {
        return '';
    } else {
        return date('m', strtotime($date)) . '-' . date('Y', strtotime($date));
    }
}

function format_quarter($date) {
    //2018-02-25  ==> 2018-Q1

    if (empty($date) || $date == '0000-00-00') {
        return '';
    } else {
        //return date('m', strtotime($date)) . '-' . date('Y', strtotime($date));
        $year = date('Y', strtotime($date));
        if ($date >= $year . '-01-01' && $date < $year . '-04-01') {
            return 'Q1-' . $year;
        } else if ($date >= $year . '-04-01' && $date < $year . '-07-01') {
            return 'Q2-' . $year;
        } else if ($date >= $year . '-07-01' && $date < $year . '-10-01') {
            return 'Q3-' . $year;
        } else {
            return 'Q4-' . $year;
        }
        // Q1-2017

        return '';
    }
}

function format_qmw_date($date_type, $date) {
    if ($date_type == 'month') {
        return format_month($date);
    } else if ($date_type == 'week') {
        return format_week($date);
    } else {
        return format_quarter($date);
    }
}

function firstDayOf($period, DateTime $date = null) {
    $period = strtolower($period);
    $validPeriods = array('year', 'quarter', 'month', 'week');

    if (!in_array($period, $validPeriods))
        throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));

    $newDate = ($date === null) ? new DateTime() : clone $date;

    switch ($period) {
        case 'year' :
            $newDate->modify('first day of january ' . $newDate->format('Y'));
            break;
        case 'quarter' :
            $month = $newDate->format('n');

            if ($month < 4) {
                $newDate->modify('first day of january ' . $newDate->format('Y'));
            } elseif ($month > 3 && $month < 7) {
                $newDate->modify('first day of april ' . $newDate->format('Y'));
            } elseif ($month > 6 && $month < 10) {
                $newDate->modify('first day of july ' . $newDate->format('Y'));
            } elseif ($month > 9) {
                $newDate->modify('first day of october ' . $newDate->format('Y'));
            }
            break;
        case 'month' :
            $newDate->modify('first day of this month');
            break;
        case 'week' :
             $newDate->modify('monday this week');
            break;
    }

    return $newDate->format('Y-m-d');
}

function is_valid($from, $to) {
    $currentDate = date('Y-m-d');
    $currentDate = date('Y-m-d', strtotime($currentDate));
    ;
    //echo $paymentDate; // echos today!
    $from = date('Y-m-d', strtotime($from));
    $to = date('Y-m-d', strtotime($to));

    if ($currentDate <= $to) {
        return true;
    } else {
        return false;
    }
}

function sub_month($from, $to) {
    $datetime1 = strtotime($from);
    $datetime2 = strtotime($to);

    $secs = $datetime2 - $datetime1;
    // == <seconds between the two times>
    if (is_valid($from, $to)) {
        $days = ceil($secs / (86400 * 30));
    } else {
        $days = 'N/A';
    }

    return $days;
}

function substruct($date) {
    return date('Y-m-d', strtotime('-1 day', strtotime($date)));
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function get_next_day($day, $date) {
    return strtotime("next " . $day, $date);
}

function daycount($startdate) {

    $enddate = strtotime(date("Y-m-t", $startdate));
    $results = array();
    $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

    foreach ($days as $day) {
        $counter = 0;
        if (date('l', $startdate) == $day) {
            $counter++;
        }
        $start_date = get_next_day($day, $startdate);
        while ($start_date <= $enddate) {
            $start_date = get_next_day($day, $start_date);
            $counter++;
        }

        $result[$day] = $counter;
    }// end foreach
    return $result;
}

/* End of file welcome.php */
/* Location: ./system/application/helpers/MY_date_helper.php */
