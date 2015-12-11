<?php
/*
* Yotsubato!
* v0.01alpha
* 
* Imageboard php software.
* Almost all php code is written by spootTheLousy, Apogate, RePod.
* 
*                 __
*    ___     __.::::::..__
* ,:::::::odP"::::::::::""Yb.
*  `::::d'":::d::d b::L:::::`b.   ___
* .:::.d':d::d:,P   b:Ib:I::::`b::::::.
*  7;' I:d .dP"      bI bIb::::`b::::::'
*  '   V'     ___     b __ b::::I`:::::.
*     ( cI  ""   ""   ""  `.b:_P   "`: '
*      \ I                   V `.
*       d'    ,"""""""".      (, )
*      b     :        :     ,  /
*     .-"b    :        ;   .d..`.
*    <::::Y._  `._____/  ,;"`::::\
*    /::::' `""=..__..,="'    \:.|"
*    "|:'        )"" (         \|`
* 
* team-ru4chan, 2015
* 
*/

require "config.php";
session_start();

require_once(CORE_DIR . "/log/log.php");
$my_log = new Log;

require_once(CORE_DIR . "/mysql/mysql.php");
$mysql = new SaguaroMySQL;
$mysql->init();

extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);
extract($_COOKIE, EXTR_SKIP);

$path = realpath("./") . '/' . IMG_DIR;
ignore_user_abort(TRUE);

// check whether the current user can perform $action (on $no, for some actions)
// board-level access is cached in $valid_cache.
function valid($action = 'moderator', $no = 0) {
    require_once(CORE_DIR . "/admin/validate.php");
    $validate = new Validation;
    return $validate->verify($action);
}

function error($mes, $dest, $fancy = 0) {
    require_once(CORE_DIR . "/general/error.php");
    $error = new Error();
    $error->format($mes, $dest, $fancy);
}

/* user image deletion */
function usrdel($no, $pwd, $onlyimgdel) {
    global $path, $pwdc, $onlyimgdel;
    require_once(CORE_DIR . "/admin/delpost.php");
    $del = new DeletePost;
    $del->userDel($no, $pwd, $onlyimgdel);
}

/*-----------Main-------------*/
switch ($mode) {
    case 'regist':
        require_once(CORE_DIR . "/regist/regist.php"); // $name, $email, $sub, $com, $url, $pwd, $resto
        break;
    case 'report':
        require_once(CORE_DIR . "/admin/report.php");
        $report = new Report;
        $report->reportProcess();
        break;
    case 'catalog':
        require_once(CORE_DIR . "/catalog/catalog.php");
        $catalog = new Catalog;
        echo $catalog->formatPage();
        break;
    case 'usrdel':
        usrdel($no, $pwd, $onlyimgdel);
    default:
        if ($res) {
            require_once(CORE_DIR . "/general/resredir.php");
            echo "<META HTTP-EQUIV='refresh' content='10;URL=" . PHP_SELF2_ABS . "'>";
        } else {
            echo "Updating index...\n";
            $my_log->update();
            echo "<META HTTP-EQUIV='refresh' content='0;URL=" . PHP_SELF2_ABS . "'>";
        }
}
?>
