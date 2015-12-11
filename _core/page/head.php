<?php

/*

    Head. Or more specifically everything before the body tag.

    $head = new Head;
    echo $head->generate();

*/

class Head {
    public $info = [ //These are the defaults used unless specified otherwise.
        'page' => [
            'title' => ''
        ],
        'css' => [
            'extra' => []
        ]
    ];

    function generate() {
        $dat = '';
        $boardTitle = '';
        $bannerImg = '';
        $headSub = '';

        if (SHOWTITLETXT > 0) {
            $boardTitle = "<div class='boardTitle'>" . TITLE . "</div>" . $headSub;
            $headSub .= '<div class="boardSubtitle">' . S_HEADSUB . '</div><hr>';
            if (SHOWTITLETXT == 2)  //you cannot stop me repod i am invincible
                $boardTitle ="<div class='boardTitle'/>/" . BOARD_DIR . "/ - " . TITLE . "</div>";
        }
        $bannerImg .= (SHOWTITLEIMG) ? '<img class="bannerImg" src="' . TITLEIMG . '" onclick="this.src=this.src;" alt="' . TITLE . '" /><br>' : '';
        $max_bits = MAX_KB*1024*8;
        /* begin page content */
        $dat .= "<!DOCTYPE html><head>
                <meta name='description' content='" . S_DESCR . "'/>
                <meta http-equiv='content-type'  content='text/html;charset=utf-8'/>
                <meta name=\"referrer\" content=\"origin\">
                <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">
                <meta http-equiv='cache-control' content='max-age=0'/>
                <meta http-equiv='cache-control' content='no-cache'/>
                <meta http-equiv='expires' content='0'/>
                <meta http-equiv='expires' content='Tue, 01 Jan 1980 1:00:00 GMT'/>
                <meta http-equiv='pragma' content='no-cache'/>
                <link rel='shortcut icon' href='" . CSS_PATH . "imgs/favicon.ico'>
                <script type=\"text/javascript\">var  style_group = \"nws_style\",  cssVersion = 639,  jsVersion = 1015,  comlen = 2000,  maxFilesize = ". $max_bits .",  maxLines = 50,  file_too_big = \"Максимальный размер файла " . MAX_KB . " KB.\",  clickable_ids = 1,  cooldowns = {\"thread\":60,\"reply\":15,\"image\":15,\"reply_intra\":15,\"image_intra\":15};var maxWebmFilesize = 3145728;var check_for_block = 1; </script>
                <title>" .  $this->info['page']['title'] ."</title>";

        if (NSFW) {
            $dat .= "<link class='togglesheet' rel='stylesheet' type='text/css' href='" . CSS_PATH . CSS1 . "' title='Yotsuba' />
                <link rel='stylesheet' type='text/css' href='" . CSS_PATH . "/stylesheets/mobile.css' title='mobile' />
                <link class='togglesheet' rel='alternate stylesheet' type='text/css' media='screen'  href='" . CSS_PATH . CSS2 . "' title='Yotsuba B' />";
        } else {
            $dat .= "<link rel=\"stylesheet\" title=\"switch\"  href='" . CSS_PATH . CSS2 . "' title='Yotsuba B' />
            <link rel='stylesheet' type='text/css' href='" . CSS_PATH . "/stylesheets/mobile.css' title='mobile' />
            <link class='togglesheet' rel='alternate stylesheet' type='text/css' href='" . CSS_PATH . CSS1 . "' title='Yotsuba' />";
        }
        //<link class='togglesheet' rel='alternate stylesheet' type='text/css' media='screen'  href='" . CSS_PATH . CSS4 . "' title='Burichan'/> RIP Burichan 1862-2015
        $dat .= "<link class='togglesheet' rel='alternate stylesheet' type='text/css' media='screen'  href='" . CSS_PATH . CSS3 . "' title='Tomorrow' />";

        $dat .= EXTRA_SHIT . '</head><body class="is_index"><div class="beforePostform" />' . $titlebar . '
                <span class="boardList desktop">' . ((file_exists(BOARDLIST)) ? file_get_contents(BOARDLIST) : ''). '</div>
                <div class="linkBar">[<a href="' . HOME . '" target="_top">' . S_HOME . '</a>][<a href="' . PHP_ASELF_ABS . '">' . S_ADMIN . '</a>]
                </span><div class="boardBanner">' . $bannerImg . $boardTitle . '</div>' . $headSub . '
                <a id="top"></a>';

        if (USE_ADS1) {
            $dat .= ADS1 . '<hr>';
        }
        $dat .= "</div>";

        return $dat;
    }
    
    function generateAdmin() {
        require_once(CORE_DIR . "/admin/report.php");

        $getReport = new Report;
        
        $boardTitle = (SHOWTITLETXT > 0) ? "<div class='boardTitle'>" . $this->info['page']['title'] . "</div><div class='boardSubtitle'>" . S_HEADSUB . "</div><hr>" : '';
        $bannerImg .= (SHOWTITLEIMG) ? '<img class="bannerImg" src="' . TITLEIMG . '" onclick="this.src=this.src;" alt="' . TITLE . '" /><br>' : '';
        
        /* begin page content */
        $dat = "<!DOCTYPE html><head>
                    <meta name='description' content='" . S_DESCR . "'/></meta>
                    <meta http-equiv='content-type'  content='text/html;charset=utf-8' /></meta>
                    <meta name='viewport' content='width=device-width, initial-scale=1'></meta>
                    <meta http-equiv='cache-control' content='max-age=0' />
                    <meta http-equiv='cache-control' content='no-cache' />
                    <meta http-equiv='expires' content='0' />
                    <meta http-equiv='expires' content='Tue, 01 Jan 1980 1:00:00 GMT' />
                    <meta http-equiv='pragma' content='no-cache' />
                    <link rel='shortcut icon' href='" . CSS_PATH . "imgs/favicon.ico'>
                    <title>" . $this->info['page']['title'] . "</title>";
        
        //$dat .= "<link class='togglesheet' rel='stylesheet' type='text/css' href='" . CSS_PATH . "/panel.css' title='Admin Panel' />";

        if (NSFW) {
            $dat .= "<link class='togglesheet' rel='stylesheet' type='text/css' href='" . CSS_PATH . CSS1 . "' title='Yotsuba' />
                <link rel='stylesheet' type='text/css' href='" . CSS_PATH . "/stylesheets/mobile.css' title='mobile' />
                <link class='togglesheet' rel='alternate stylesheet' type='text/css' media='screen'  href='" . CSS_PATH . CSS2 . "' title='Yotsuba B' />";
        } else {
            $dat .= "<link class='togglesheet' rel='stylesheet' type='text/css' media='screen'  href='" . CSS_PATH . CSS2 . "' title='Yotsuba B' />
            <link rel='stylesheet' type='text/css' href='" . CSS_PATH . "/stylesheets/mobile.css' title='mobile' />
            <link class='togglesheet' rel='alternate stylesheet' type='text/css' href='" . CSS_PATH . CSS1 . "' title='Yotsuba' />";
        }
        //<link class='togglesheet' rel='alternate stylesheet' type='text/css' media='screen'  href='" . CSS_PATH . CSS4 . "' title='Burichan'/> RIP Burichan 1862-2015
        $dat .= "<link class='togglesheet' rel='alternate stylesheet' type='text/css' media='screen'  href='" . CSS_PATH . CSS3 . "' title='Tomorrow' />";        
        
        $dat .= "<script src='" . JS_PATH . "/extension.min.js' type='text/javascript'></script>
                <script src='" . JS_PATH . "/main.js' type='text/javascript'></script>";
        
        $dat .= '</head><div class="beforePostform" />' . $titlebar . '
                <span class="boardList desktop">' . ((file_exists(BOARDLIST)) ? file_get_contents(BOARDLIST) : '') . '</div>
                <div class="linkBar">[<a href="' . HOME . '" target="_top">' . S_HOME . '</a>][<a href="' . PHP_ASELF_ABS . '">' . S_ADMIN . '</a>]
                </span><div class="boardBanner">' . $bannerImg . $boardTitle . '</div>';            
        
        $dat .= "<div class='panelOps' style='text-align:left;' />[<a href=\"" . PHP_SELF2 . "\">" . S_RETURNS . "</a>]";
        $dat .= "[<a href=\"" . PHP_SELF . "\">" . S_LOGUPD . "</a>]";
        if (valid('moderator')) {
            $dat .= "[<a href='" . PHP_ASELF_ABS . "?mode=rebuild' >Пересобрать</a>]";
            $dat .= "[<a href='" . PHP_ASELF_ABS . "?mode=rebuildall' >Пересобрать все треды</a>]";
            $dat .= "[<a href='" . PHP_ASELF_ABS . "?mode=reports' >" . $getReport->reportGetAllBoard() . "</a>]";
        }
        if (valid('admin'))
            $dat .= "[<a href='" . PHP_ASELF_ABS . "?mode=staff' >Управление пользователями</a>]";
        $dat .= "[<a href='" . PHP_ASELF . "?mode=logout'>" . S_LOGOUT . "</a>]";
        return $dat;
    } 
}

?>
