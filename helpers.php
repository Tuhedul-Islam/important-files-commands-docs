<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Get organization date format
 * @returns string
 */
 function orgDateFormat($date): string{
    $site_setting_info = \App\Models\SiteSetting::first();
    $get_date_format = isset($site_setting_info->date_format) ? $site_setting_info->date_format : 'Y-m-d';
    return date($get_date_format, strtotime($date));
}

/**
 * Get attendance work hour
 * @returns string
 */
 function numberToWords($number){
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = $words[$point];
    $paisa=$points!=null?$points . " paisa":null;

    if ($paisa!=null){
        //return ucfirst($result) . "taka and " .$paisa.' 0nly';
        return ucfirst($result);
    }else{
        //return ucfirst($result) . "taka " .$paisa.' 0nly';
        return ucfirst($result);
    }

}

/**
 * Get user language
 * @returns string
 */
 function getUserLanguage(): string{
    if ((Auth::check()) && (Auth::user()->language != null)) {
        $language = Auth::user()->language;
    } else {
        $language = "en";
    }
    return $language;
}

/**
 * Convert title
 * @returns string
 */
 function titleConverter($title): string{
    return strtolower(str_ireplace([':', '\\', '/', '*', ' '], '_', $title));
}

/**
 * Convert title
 * @returns string
 */
 function getDomainExtensions(): array{
    return array('.com', '.com.bd', '.aero', '.asia', '.biz', '.cat', '.coop', '.edu', '.gov', '.gov.bd', '.info', '.int', '.jobs', '.mil', '.mobi', '.museum', '.name', '.net', '.org', '.pro', '.tel', '.travel', '.co', '.co.uk');
}

/**
 * Get user name by id
 * @returns string
 * @param int $id
 */
 function getUserName($id): string{
    return User::find($id)->full_name ?? "N/A";
}

/**
 * Get number to word amount
 * @returns string
 * @param $number
 */
 function number_to_words($num = ''){
    $num = ( string )(( int )$num);

    if (( int )($num) && ctype_digit($num)) {
        $words = array();

        $num = str_replace(array(',', ' '), '', trim($num));

        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven',
            'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen',
            'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');

        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty',
            'seventy', 'eighty', 'ninety', 'hundred');

        $list3 = array('', 'thousand', 'million', 'billion', 'trillion',
            'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion',
            'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion',
            'octodecillion', 'novemdecillion', 'vigintillion');

        $num_length = strlen($num);
        $levels = ( int )(($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);

        foreach ($num_levels as $num_part) {
            $levels--;
            $hundreds = ( int )($num_part / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
            $tens = ( int )($num_part % 100);
            $singles = '';

            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = ( int )($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = ( int )($num_part % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && ( int )($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
        }
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        $words = implode(', ', $words);

        $words = trim(str_replace(' ,', ',', ucwords($words)), ', ');
        if ($commas) {
            $words = str_replace(',', ' and', $words);
        }

        return $words;
    } else if (!(( int )$num)) {
        return 'Zero';
    }
    return '';
}

/**
 * Display required star dynamically
 */
 function starSign(){
    return "<span class='required_star'>" . " *" . "</span>";
}

/**
 * Get sanitized data
 * @returns string
 * @param $string
 */
 function escape_output($string){
    if ($string) {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    } else {
        return '';
    }
}

/**
 * Get decrypted string ulr from id
 * @returns string
 */
 function encrypt_decrypt($key, $type){
    # type = encrypt/decrypt
    $str_rand = "XxOx*4e!hQqG5b~9a";

    if (!$key) {
        return false;
    }
    if ($type == 'decrypt') {
        $en_slash_added = trim(str_replace(array('ticketly'), '/', $key));
        $key_value = $return = openssl_decrypt($en_slash_added, "AES-128-ECB", $str_rand);
        return $key_value;

    } elseif ($type == 'encrypt') {
        $key_value = openssl_encrypt($key, "AES-128-ECB", $str_rand);
        $en_slash_remove = trim(str_replace(array('/'), 'ticketly', $key_value));
        return $en_slash_remove;
    }
    return FALSE;    # if public static function is not used properly
}

 function uploadedFileSize($size, $precision = 2){
    if ($size > 0) {
        $size = (int)$size;
        $base = log($size) / log(1024);
        $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }
    return $size;
}

 function uploadedFileSizeInMb($size, $precision = 2){
    if ($size > 0) {
        $mb = number_format($size / 1048576, 2);
        return $mb;
    }
    return $size;
}

 function showTotalNotification(){
    $data = \App\Model\Notification::whereNull('mark_as_read_status')->where('del_status', 'Live')->count();
    return $data;
}

//Get Hour from time
 function getTotalHour($start_time, $end_time){
    $time1 = $start_time;
    $time2 = $end_time;
    $array1 = explode(':', $time1);
    $array2 = explode(':', $time2);

    $minutes1 = ($array1[0] * 60.0 + $array1[1]);
    $minutes2 = ($array2[0] * 60.0 + $array2[1]);

    $total_min = $minutes1 - $minutes2;
    $total_tmp_hour = (int)($total_min / 60);
    $total_tmp_hour_minus = ($total_min % 60);

    //return $total_tmp_hour.".".$total_min_tmp;
    return $total_tmp_hour . "." . $total_tmp_hour_minus;

}

//Get Hour from time
 function activityLog($ticket_id = null, $type = null, $mentioned = null){
    $now_time = \Illuminate\Support\Carbon::now();
    $date = date('d-m-Y', strtotime($now_time));
    $time = date('h:i a', strtotime($now_time));
    $mentioned_data = '';
    if (!empty($mentioned)) {
        $mentioned_data = implode(', ', $mentioned);
    }

    if ($type == 'mentioned') {
        $full_text = "A " . Auth::user()->type . " " . Auth::user()->getFullNameAttribute() . " " . $type . " (" . $mentioned_data . ") a ticket on " . $date . " at " . $time . ", ticket id is " . $ticket_id;
    } else {
        $full_text = "A " . Auth::user()->type . " " . Auth::user()->getFullNameAttribute() . " " . $type . " a ticket on " . $date . " at " . $time . ", ticket id is " . $ticket_id;
    }

    //add activity
    $activity_info = new ActivityLog();
    $activity_info->type = $type;
    $activity_info->user_id = Auth::user()->id;
    $activity_info->activity = $full_text;
    $activity_info->save();
    return 0;
}
