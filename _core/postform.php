<?php
/*

    Generates the post form.

    Needs to be heavily revised.

    $postform->format()
        Generates a post form for a new thread.
    $postform->format(1)
        Generates a post form to reply to a thread (1).

*/

class PostForm {
    function format($resno = null, $admin = false) {
        //echo debug_backtrace()[1]['function'];

        $resno = (is_numeric($resno)) ? $resno : null;
        $admin = (!!$admin) ? !!$admin : false; //Should probably move validation to something more secure.

        $maxbyte = MAX_KB * 1024;
        $temp = "";

        if ($resno) $temp .= "<div class='theader'>" . S_POSTING . "</div>\n";

        $temp .= "<div class='postForm' align='center'><div class='postarea'>";
        //$temp .= "<form id='contribform' action='" . PHP_SELF_ABS . "' method='post' name='contrib' enctype='multipart/form-data'>";
        $temp .= "<form name=\"post\" action=\"//".SITE_ROOT."/".BOARD_DIR."/post\" method=\"post\" enctype=\"multipart/form-data\">";

        if ($admin) {
            $name = "";

            if (valid('moderator')) {
                $name = '<span style="color:#770099;font-weight:bold;">Анонимус ## Mod</span>';
            }
            if (valid('admin')) {
                $name = '<span style="color:#FF101A;font-weight:bold;">moot ## Admin</span>';
            }
            if (valid('manager')) {
                $name = '<span style="color:#2E2EFE;font-weight:bold;">Yotsubato ## Coder</span>';
            }

            $temp .= "<em>" . S_NOTAGS . " Вы постите от имени</em>: " . $name;
            $temp .= "<input type='hidden' name='admin' value='" . PANEL_PASS . "'>";
        }

        $temp .= "<input type='hidden' name='mode' value='regist'><input type='hidden' name='MAX_FILE_SIZE' value='" . $maxbyte . "'>";

        if ($resno)
            $temp .= "<input type='hidden' name='resto' value='" . $resno . "'>";

        $temp .= "<table style=\"display: table;\" class=\"postForm hideMobile\" id=\"postForm\">";

        if (!FORCED_ANON) //Name
            //$temp .= "<tr><td class='postblock' align='left'>" . S_NAME . "</td><td align='left'><input type='text' name='name' size='28'></td></tr>";
	    $temp .= "<tr data-type='Name'><td>".S_NAME."</td><td><input name='name' tabindex='1' placeholder='".S_ANONAME."' type='text'></td> </tr>";

        //$temp .= "<tr><td class='postblock' align='left'>" . S_EMAIL . "</td><td align='left'><input type='text' name='email' size='28'>";
        if ($resno) //Subject if a new thread.
	$temp .= "<tr data-type='E-mail'> <td>". S_EMAIL ."</td> <td><input name='email' tabindex='2' type='text'><input value='" . S_SUBMIT . "' tabindex=\"6\" type=\"submit\"></td></tr>";
        else
	$temp .= "<tr data-type='E-mail'> <td>". S_EMAIL ."</td> <td><input name='email' tabindex='2' type='text'></td> </tr>";

        if (!$resno) //Subject if a new thread.
             $temp .= "</td></tr><tr data-type='Subject'> <td>" . S_SUBJECT . "</td> <td><input name=\"sub\" tabindex=\"3\" type=\"text\"><input value='" . S_SUBMIT . "' tabindex=\"6\" type=\"submit\"></td></tr>";
             //$temp .= "</td></tr><tr><td class='postblock' align='left'>" . S_SUBJECT . "</td><td align='left'><input type='text' name='sub' size='35'>";

        //$temp .= "<input value='" . S_SUBMIT . "' tabindex=\"6\" type=\"submit\"></td></tr>";
        //$temp .= "<input type='submit' value='" . S_SUBMIT . "'></td></tr>";

        $temp .= "<tr data-type=\"Comment\"><td>" . S_COMMENT . "</td><td><textarea name=\"com\" cols=\"48\" rows=\"4\" tabindex=\"4\" wrap=\"soft\"></textarea></td></tr>";
//        $temp .= "<tr data-type="Comment"><td>" . S_COMMENT . "</td><td align='left'><textarea name='com' cols='34' rows='4'></textarea></td></tr>";

        if (BOTCHECK && !$admin) { //Captcha
            if (RECAPTCHA) {
                $temp .= "<tr id=\"captchaFormPart\"><td class=\"desktop\">Verification</td><td colspan=\"2\"><script src='//www.google.com/recaptcha/api.js'></script><div class='g-recaptcha' data-sitekey='" . RECAPTCHA_SITEKEY ."'></div></tr>";
            } else {
                $temp .= "<tr><td class='postblock' align='left'><img src='" . CORE_DIR_PUBLIC . "/general/captcha.php' /></td><td align='left'><input type='text' name='num' size='28'></td></tr>";
            }
        }

        //File selection
        $temp .= "<tr data-type=\"File\"><td>" . S_UPLOADFILE . "</td><td><input id=\"postFile\" name=\"upfile\" tabindex=\"7\" type=\"file\"><span class=\"desktop\"></span></td></tr>";

        //$temp .= "<tr><td class='postblock' align='left'>" . S_UPLOADFILE . "</td><td><input type='file' name='upfile' accept='image/*|.webm' size='35'>";

        if (NOPICBOX && !SPOILER)
            $temp .= "<span class=\"desktop\">[<label><input name='textonly' value='on' tabindex=\"8\" type=\"checkbox\">" . S_NOFILE . "</label>]</span>";
	//Lols
        /*if (SPOILER && !NOPICBOX) //Spoiler checkbox
            $temp .= "[<label><input type='checkbox' name='spoiler' value='spoiler'>" . S_SPOILERS . "</label>]</td></tr>";
        else*/
            $temp .= "</td></tr>";

        if ($admin) { //Admin-specific posting options
            $temp .= "<tr><td align='left' class='postblock' align='left'>
                Опции</td><td align='left'>
                Закреплено: <input type='checkbox' name='isSticky' value='isSticky'>
                Event sticky: <input type='checkbox' name='eventSticky' value='eventSticky'>
                Закрыто:<input type='checkbox' name='isLocked' value='isLocked'>
                ##:<input type='checkbox' name='showCap' value='showCap'>
                <tr><td class='postblock' align='left'>" . S_RESNUM . "</td><td align='left'><input type='text' name='resto' size='28'></td></tr>";
        }

        //Deletion password entry
        $temp .= "<tr><td align='left' class='postblock' align='left'>" . S_DELPASS . "</td><td align='left'><input type='password' name='pwd' size='8' maxlength='8' value='' />" . S_DELEXPL . "</td></tr>";

        if (!$admin) //Show rules for non-admin
            $temp .= "<tr class=\"rules\"><td colspan='2'><div align='left' class='rules'>" . S_RULES . "</div></td></tr></table></form></div></div><hr>";
        else
            $temp .= '</table></form></div></div>';

        if (file_exists(GLOBAL_NEWS)) {
            $news = file_get_contents(GLOBAL_NEWS);

            if ($news !== "")
                $temp .= "<div class='globalNews desktop'>" . file_get_contents( GLOBAL_NEWS ) . "</div><hr>";
                $temp .= "<div class='globalNewsM mobile'>" . file_get_contents( GLOBAL_NEWS ) . "<hr></div>";
        }

        if ($resno) //Navigation bar above thread.
            $temp .= "<div class='threadnav' /> [<a href='" . PHP_SELF2_ABS . "'>" . S_RETURN . "</a>] [<a href='" . $resno . PHP_EXT . "#bottom'/>Bottom</a>] </div>\n<hr>";
        else
            $temp .= "<div class='threadnav' /> [<a href='" . PHP_SELF2_ABS . "#bottom'/>Bottom</a>]  [<a href='/" . BOARD_DIR . "/" . PHP_SELF . "?mode=catalog'>Catalog</a>]</div><hr>";
        if (USE_ADS2) $temp .= ADS2 . "<hr>";

        return $temp;
    }
}

?>
