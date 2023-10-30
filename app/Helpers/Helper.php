<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

function parent__construct()
{
    $verification = 1;
    if ($verification) {
        $r = \DB::table('admin_login')->select('id')
            ->where('id', getsession('id'))
            ->where('username', getsession('username'))
            ->where('status', 1)
            ->limit(1)
            ->get();

        if (!$r) {
            // return redirect()->to('/');
            // die();
        }


        $urls = request()->segments(0);
        $permission = \DB::table('admin_permission')->select(array('admin_permission.id', 'admin_permission.fields', 'a2.name'))
            ->join('admin_page as a2', 'a2.id', '=', 'admin_permission.page_id', 'left outer')
            ->where('admin_permission.user_id', getsession('id'))
            ->where('a2.url', $urls)
            ->limit(1)
            ->get();

        $p = $permission->toArray();

        if (!$p) {
            // return redirect()->to('/');
            // die();
        }
        $p = object_to_array($p);
        return $p;
    }
}

function get_setting($classname, $mth, $type = '')
{
    $query = \DB::table('admin_view_permission')->select('fields')
        ->where('user_id', getsession('id'))
        ->where('page', $classname)
        ->limit(1)
        ->get();

    $x = $query->toArray();
    if ($x) {
        $x = object_to_array($x);
        $nths = explode(',', $x[0]['fields']);
    } else {
        $nths = $mth;
    }

    $ath = explode(',', s_result("admin_page", "url", "fields", $classname));
    if (count($ath)) {
        $tid = 0;
        foreach ($ath as $thk => $thr) {
            if (in_array($thr, $mth)) {
                $checked = "";
                if (in_array($thr, $nths)) {
                    $checked = "checked";
                }
                if ($type != 1) {
                    $thk = $tid;
                    $tid++;
                }
                echo '<div class="custom-control custom-switch" data-view="' . $thk . '"> <input value="' . $thr . '" ' . $checked . ' type="checkbox" name="per[]" class="custom-control-input" id="csgo' . $thk . '"> <label class="custom-control-label" for="csgo' . $thk . '">&nbsp;' . ucwords($thr) . '</label> </div>';
            }
        }
    }
}
function yesno($val, $type = '')
{
    $positive = ($type) ? 'Allowed' : 'Yes';
    $negative = ($type) ? 'Not Allowed' : 'No';;
    return ($val == 1) ? $positive : $negative;
}
function object_to_array($data)
{
    if (is_array($data) || is_object($data)) {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = (is_array($data) || is_object($data)) ? object_to_array($value) : $value;
        }
        return $result;
    }
    return $data;
}

function s_result($table, $where, $select, $value)
{
    $select = explode(',', $select);
    $r = \DB::table($table)
        ->select($select)
        ->where($where, '=', $value)
        ->limit(1)
        ->get()
        ->toArray();
    $r = object_to_array($r);
    if (!count($r)) {
        return false;
    }
    if (count($select) > 1) {
        return $r[0];
    } else {
        return $r[0][$select[0]];
    }
}

function s_result_multi($table, $where, $select)
{
    $select = explode(',', $select);
    $query = \DB::table($table)
        ->select($select)
        ->limit(1);
    foreach ($where as $key => $value) {
        $query->where($key, '=', $value);
    }
    $r = $query->get()
        ->toArray();
    $r = object_to_array($r);
    if (!count($r)) {
        return false;
    }
    if (count($select) > 1) {
        return $r[0];
    } else {
        return $r[0][$select[0]];
    }
}

function getNextStatus($id, $status, $model_id = '')
{
    $mk = s_result('vessel', 'id', 'model_id', $id);
    if ($model_id) {
        $mk = $model_id;
    }
    $select = ['is_jacket', 'is_agitator', 'is_insulation'];
    $r = \DB::table('master_model')
        ->select($select)
        ->where('id', $mk)
        ->limit(1)
        ->get()
        ->toArray();
    $r = object_to_array($r);
    if (count($r) > 0) {
        $v = $r[0];
        $statusR = $status;
        if ($status == 2) {
            if ($v['is_jacket'] != 1) $statusR++;
            if ($v['is_agitator'] != 1 && $statusR != $status) $statusR++;
            if ($v['is_insulation'] != 1 && $statusR != $status) $statusR++;
        }
        if ($status == 3) {
            if ($v['is_agitator'] != 1) $statusR++;
            if ($v['is_insulation'] != 1 && $statusR != $status) $statusR++;
        }
        if ($status == 4) {
            if ($v['is_insulation'] != 1) $statusR++;
        }
        return $statusR;
    } else {
        return $status;
    }
}

function format_uri($string, $separator = '-', $third = '')
{
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array('&' => 'and', "'" => '');
    $string = mb_strtolower(trim($string), 'UTF-8');
    $string = strtolower($string);
    $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
    $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
    if (!$third) {
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    }
    return $string;
}

function in_array_any($needles, $haystack)
{
    return !empty(array_intersect($needles, $haystack));
}

function shortnum($num)
{
    $units = ['', 'K', 'M', 'B', 'T'];
    for ($i = 0; $num >= 1000; $i++) {
        $num /= 1000;
    }
    return round($num, 1) . $units[$i];
}

function dateind($date = '')
{
    if (!$date) {
        return '';
    }
    if ($date) {
        if (strtotime($date)) {
            return date('d/m/Y', strtotime($date));
        } else {
            return date_format(date_create($date), 'd/m/Y');
        }
    }
}
function datex($date = '')
{
    if (!$date) {
        return '';
    }
    if ($date) {
        if (strtotime($date)) {
            return [date('d M Y', strtotime($date)), date('h:i A', strtotime($date))];
        } else {
            return '';
        }
    }
}

function convertdate($date = '')
{
    if (!$date) {
        return '';
    }
    $date = str_replace("/", "-", $date);
    if (strtotime($date)) {
        $dates = date('Y-m-d', strtotime($date));
    } else {
        $dates = date_format(date_create($date), 'Y-m-d');
    }
    return $dates;
}

function convertdatefull($date)
{
    $date = str_replace("/", "-", $date);
    if (strtotime($date)) {
        $dates = date('Y-m-d H:i:s', strtotime($date));
    } else {
        $dates = date_format(date_create($date), 'Y-m-d H:i:s');
    }
    return $dates;
}

function dateforJavascript($date)
{
    if (!$date) {
        return false;
    }
    return date('d/m/Y H:i', strtotime($date));
}

function datefor($date, $type = '')
{
    if (!$date) {
        return false;
    }
    if ($type == 'fdy') {
        return date('F d, Y', strtotime($date)) . " " . date('h:i a', strtotime($date));
    } else if ($type) {
        return date('d M, Y', strtotime($date)) . "<br>" . date('h:i a', strtotime($date));
    } else {
        return date('d/m/Y h:i A', strtotime($date));
    }
}

function timeago($datetime, $full = false, $gujarati = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
    );
    if ($gujarati) {
        $string = array(
            'y' => 'વર્ષ',
            'm' => 'માસ',
            'w' => 'સપ્તાહ',
            'd' => 'દિવસ',
            'h' => 'કલાક',
            'i' => 'મિનિટ',
        );
    }
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            if (!$gujarati) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            }
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    if ($gujarati) {
        return $string ? implode(', ', $string) . ' પહેલા' : 'અત્યારે';
    } else {
        return $string ? implode(', ', $string) . ' ago' : 'Just Now';
    }
}

function admin_setting($id = '')
{
    $query = \DB::table('admin_setting')->where('id', '=', $id)->limit(1)->get();
    if (count($query)) {
        return $query[0]->value;
    }
    return '';
}

function group_assoc($array, $key)
{
    $return = array();
    foreach ($array as $v) {
        $return[$v[$key]][] = $v;
    }
    return $return;
}

function getsession($value = '', $table = 'user')
{
    $getUser = \Request::session()->get($table);
    if ($getUser) {
        return $getUser[0][$value];
    }
    return false;
}

function status_badge($status)
{
    $sb = '';
    if ($status == 0) {
        $sb = '<span class="badge badge-danger">Deleted</span>';
    } elseif ($status == 1) {
        $sb = '<span class="badge badge-success">Published</span>';
    } elseif ($status == 2) {
        $sb = '<span class="badge badge-danger">Deactivated</span>';
    } elseif ($status == 3) {
        $sb = '<span class="badge badge-warning">Draft</span>';
    } elseif ($status == 4) {
        $sb = '<span class="badge badge-info">Scheduled</span>';
    } elseif ($status == 5) {
        $sb = '<span class="badge badge-dark">For Approve</span>';
    } elseif ($status == 6) {
        $sb = '<span class="badge badge-danger">Trashed</span>';
    }
    return $sb;
}

function status_badge_approval($status)
{
    $sb = '';
    if ($status === 0) {
        $sb = '<span class="badge badge-primary">Pending</span>';
    } elseif ($status === 1) {
        $sb = '<span class="badge badge-success">Approved</span>';
    } else if ($status === 2) {
        $sb = '<span class="badge badge-danger">Rejected</span>';
    } else if ($status === 3) {
        $sb = '<span class="badge badge-dark">Pending For Barred Approver</span>';
    }
    return $sb;
}

function get_client_ip()
{
    $ipaddress = 'UNKNOWN';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    return $ipaddress;
}


function getBrowser($u_agent = '')
{
    $bname = 'Unknown';
    $platform = 'Unknown';
    if (!$u_agent) $u_agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/OPR/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    } elseif (preg_match('/Edge/i', $u_agent)) {
        $bname = 'Edge';
        $ub = "Edge";
    } elseif (preg_match('/Trident/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
    }
    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }
    if ($version == null || $version == "") {
        $version = "?";
    }
    return array(
        //        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        //        'pattern' => $pattern
    );
}


function os_info($uagent = '')
{
    if (!$uagent) $uagent = $_SERVER['HTTP_USER_AGENT'];
    $oses = array(
        'Win311' => 'Win16',
        'Win95' => '(Windows 95)|(Win95)|(Windows_95)',
        'WinME' => '(Windows 98)|(Win 9x 4.90)|(Windows ME)',
        'Win98' => '(Windows 98)|(Win98)',
        'Win2000' => '(Windows NT 5.0)|(Windows 2000)',
        'WinXP' => '(Windows NT 5.1)|(Windows XP)',
        'WinServer2003' => '(Windows NT 5.2)',
        'WinVista' => '(Windows NT 6.0)',
        'Windows 7' => '(Windows NT 6.1)',
        'Windows 8' => '(Windows NT 6.2)',
        'Windows 8.1' => '(Windows NT 6.3)',
        'Windows 10' => '(Windows NT 10.0)',
        'WinNT' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
        'OpenBSD' => 'OpenBSD',
        'SunOS' => 'SunOS',
        'Ubuntu' => 'Ubuntu',
        'Android' => 'Android',
        'Linux' => '(Linux)|(X11)',
        'iPhone' => 'iPhone',
        'iPad' => 'iPad',
        'MacOS' => '(Mac_PowerPC)|(Macintosh)',
        'QNX' => 'QNX',
        'BeOS' => 'BeOS',
        'OS2' => 'OS/2',
        'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
    );
    $uagent = strtolower($uagent ? $uagent : $_SERVER['HTTP_USER_AGENT']);
    foreach ($oses as $os => $pattern)
        if (preg_match('/' . $pattern . '/i', $uagent))
            return $os;
    return 'Unknown';
}

function enc($action, $string)
{
    if ($string == '') return '';
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'deWLmQHVzBFGr5M3';
    $secret_iv = 'dD4knW4TeXNJFGM5';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == '1') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == '2') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function bot__enc($type = '', $num = '', $b = 62)
{
    $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($type == 1) {
        $r = $num % $b;
        $res = $base[$r];
        $q = floor($num / $b);
        while ($q) {
            $r = $q % $b;
            $q = floor($q / $b);
            $res = $base[$r] . $res;
        }
        return $res;
    } else {
        $limit = strlen($num);
        $res = strpos($base, $num[0]);
        for ($i = 1; $i < $limit; $i++) {
            $res = $b * $res + strpos($base, $num[$i]);
        }
        return $res;
    }
}

function fsize($size)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

function texttourl($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = strtolower($string);
    return preg_replace('/[^A-Za-z0-9-]/', '', $string); // Removes special chars.
}

function generatePageTree($datas, $depth = 0, $parent = 0)
{
    if ($depth > 1000) return 'xx';
    $tree = '';
    for ($i = 0, $ni = count($datas); $i < $ni; $i++) {
        if ($datas[$i]->parent_id == $parent) {
            //                $tree .= str_repeat('^^', $depth) . '|';
            $tree .= $depth . '^^|^^';
            $tree .= $datas[$i]->id . '^^|^^' . $datas[$i]->status . '^^|^^' . $datas[$i]->name . '^^|^^' . $datas[$i]->display_name;
            $tree .= PHP_EOL;
            $tree .= generatePageTree($datas, $depth + 1, $datas[$i]->id);
        }
    }
    return $tree;
}


function alladmins()
{
    $table = 'admin_login';
    $arraym = \DB::table($table)->where('status', 1)
        ->select(['id', 'name'])
        ->get()
        ->toArray();
    return $arraym;
}

function return_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val[strlen($val) - 1]);
    $val = (int)str_replace($last, '', strtolower($val));
    switch ($last) {
            // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= (1024 * 1024 * 1024); //1073741824
            break;
        case 'm':
            $val *= (1024 * 1024); //1048576
            break;
        case 'k':
            $val *= 1024;
            break;
    }

    return $val / (1024 * 1024);
}


function nlink($link, $icon, $target = '')
{
    $pages = '';
    if ($target) {
        $pages = "target='_blank'";
    }
    return '<a ' . $pages . ' href="' . url($link) . '"><i class="mdi mdi-' . $icon . '"></i></a>';
}
function exl_search($ar = array())
{
    echo "<div class='searchby_div' data-link='" . str_replace('/all', '', '') . "'>";
    echo "<input type='text' id='more_datatbl'>";
    echo "<div class='table__view'>";
    foreach ($ar as $k => $n) {
        echo "<div class='search_step lxtr" . $n . "'>";
        if ($n) {
            echo "<label>" . $n . "</label>";
            if ($n == "Date") {
                $extraclass = '';
                if ($n == "Date") {
                    $extraclass = 'simpledate';
                }
                echo "<input type='text' class='form-control ajax_s " . $extraclass . "' name='" . $k . "'>";
            } else {
                //                echo "<select class='ajax_s' name='" . $k . "'></select>";
                echo "<input type='text' class='form-control ajax_s' name='" . $k . "'>";
            }
        }
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
}
function mailtemplate($msg)
{
    //    $img = cdnAssets('assets/img/logo.jpg');
    $return = "<div style='font-size:15px;font-family:Google Sans,Roboto,RobotoDraft,Helvetica,Arial,sans-serif;text-align: center;background: #eee;padding-top: 35px;'>";
    //    $return .= "<img src='" . $img . "' style='width: 250px;'><br>";
    $return .= "<p style='color:#000;margin: 25px auto 55px auto;border-left: 3px solid #cb3725;padding:15px;display:inline-block;border-radius:4px;text-align: left;width: 400px;max-width: 100%;background: #fff;'>";
    $return .= $msg;
    $return .= "</div>";
    $return .= "</div>";
    return $return;
}

function sendmail($subject, $msg, $email, $cc = '', $template = '')
{
    $smtpsettings = admin_setting(5);
    $smtpsettings = json_decode($smtpsettings, true);

    $sharemsg = admin_setting(26);
    //    $msg = $msg . '<br><br>' . $sharemsg;


    require_once base_path() . '/public/assets/PHPMailer/PHPMailer.php';
    require_once base_path() . '/public/assets/PHPMailer/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    $mail->isSMTP();
    $mail->Host = $smtpsettings['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $smtpsettings['username'];
    $mail->Password = $smtpsettings['password'];
    $mail->Port = $smtpsettings['port'];
    $mail->setFrom($smtpsettings['from'], 'Sandesh News');
    $mail->addAddress($email);

    if ($cc) {
        $mail->addCC($cc);
    }

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $msg_ = $msg;
    if ($template) {
        $msg = mailtemplate($msg);
    }
    $mail->Body = $msg;

    if ($mail->send()) {
        $gnts = 1;
        $rt = 1;
    } else {
        $gnts = 2;
        $rt = $mail->ErrorInfo;
    }

    \DB::table('email_history')->insert(array(
        'to_email' => $email,
        'cc_email' => ($cc) ? $cc : '',
        'subject' => $subject,
        'message' => $msg_,
        'attachment' => '',
        'status' => $gnts,
        'l_date' => date('Y-m-d H:i:s')
    ));
    return $rt;
}


function mis_time($time)
{
    if ($time < 1) {
        return '';
    }
    $seconds = round($time);
    $output = sprintf('%02d:%02d', ($seconds / 60 % 60), $seconds % 60);
    if (floor($seconds / 3600) > 0) {
        $output = sprintf('%02d:%02d:%02d', ($seconds / 3600), ($seconds / 60 % 60), $seconds % 60);
    }
    return $output;
    //    $hours = floor($time / 60);
    //    $minutes = ($time % 60);
    //    return sprintf($format, $hours, $minutes);
}


function get_user_permission($type = '')
{
    $getUser = \Request::session()->get('permission');
    if (!isset($getUser[0])) {
        return array();
    }
    $permission = $getUser[0];
    $permission = json_decode(json_encode($permission), true);
    $permission = group_assoc($permission, 'page_id');

    if ($type) {
        $permission = array_keys($permission);
    }
    return $permission;
}

function formActive($status)
{
    if ($status == 2) return '<p class="erotrs">This form does not support </p>';
    return false;
}

function optionlistnew($table, $where1, $where2, $a, $b, $c = "", $val = "")
{
    $query =  \DB::table($table)->orderBy($a, "ASC")
        ->where($where1, $where2)
        ->get()
        ->toArray();

    $redata = "";
    $rr = object_to_array($query);

    foreach ($rr as $r) {
        $addextra = "";
        if ($c) {
            $addextra = " - " . $r[$c];
        }
        $display = "";
        if ($val) {
            if (in_array($r[$a], $val)) {
                $display = "selected";
            }
        }
        $redata .= '<option value="' . $r[$a] . '" ' . $display . '>' . $r[$b] . $addextra . '</option>';
    }
    return $redata;
}
