<?php //todo make utf-8 compliant
error_reporting(0);
ini_set('display_errors', 0);
date_default_timezone_set('Europe/Kiev');
setlocale(LC_CTYPE, '');
mb_internal_encoding('windows-1251');


if (!empty($_REQUEST['cyr']) && isset($_REQUEST['do']) && $_REQUEST['do'] == 'GO') {
    if (isset($_REQUEST['pref']) && !empty($_REQUEST['pref'])) {
        $pref = '/'.strTraslit($_REQUEST['pref']);
    }
    $result = @$pref . strTraslit($_REQUEST['cyr'], '/');
}

// functions
/*---------------------------------------------------------------------------------------------*/
function strTraslit($st, $enclosure = null) {
    /*$st = strtr($st,
        "абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭі",
        "abvgdegzijklmnoprstyfueABVGDEGZIJKLMNOPRSTYFUEi"
    );    */

    $cyr = mb_strtolower($st);
    $cyr = strtr($cyr,
        "абвгдежзийклмнопрстуфыэі—–",
        "abvgdegzijklmnoprstyfuei--"
    );
    $cyr = strtr(
        $cyr,
        array(
            "ё"=>"jo",    "х"=>"h",  "ц"=>"ts",   "ч"=>"ch",    "ш"=>"sh",
            "щ"=>"shch",  "ъ"=>"",   "ь"=>"",     "ю"=>"ju",    "я"=>"ja",
            "ї"=>"ji",    "є"=>"je", "&"=>"-and-","@"=> "-at-", "№"=>"-num-",
            "«"=>"",	  "»"=>""
        )
    );
    // Delete multiple and start/end hyphens
    $cyr = preg_replace_callback(
        '#^([[:punct:][:space:]]*)(.+?)([[:punct:][:space:]]*)$#',
        function($p) {
            return preg_replace('#([[:punct:][:space:]]+)#', '-', $p[2]);
        },
        $cyr
    );
    return $enclosure . $cyr . $enclosure;
}

function quotesMagic($str)
{
    $res = htmlspecialchars($str, ENT_QUOTES, 'windows-1251');
    if (get_magic_quotes_gpc()) {
        return stripslashes($res);
    }
    return $res;

}
/*---------------------------------------------------------------------------------------------*/

// Input-output form
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="windows-1251" />
    <title>Simple URI transliteration</title>
</head>
<body>
<form style="width:640px;margin:auto" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="get" enctype="application/x-www-form-urlencoded">
    <h1 style="color: #d7ceb2;text-shadow: 3px 3px 0px #2c2e38, 5px 5px 0px #5c5f72;font: 60px 'BazarMedium';letter-spacing:10px">Simple URI transliteration</h1>
    <input placeholder="Prefix, not necessarily" onclick="this.focus();this.select();" style="padding:5px" size="50" type="text" name="pref" value="<?php echo @quotesMagic($_REQUEST['pref']);?>">
    <input placeholder="String, required" onclick="this.focus();this.select();" style="padding:5px;margin-top:10px" size="100" type="text" name="cyr" value="<?php echo @quotesMagic($_REQUEST['cyr']);?>">
    <div style="width:640px">
        <input style="float:left;width:150px;height:50px;margin:15px 0;font-size:larger" type="submit" name="do" value="GO">
        <?php if (isset($result) && ($uriLength = strlen($result))): ?>
            <div style="float:left;color:#555;margin:30px"><em>URI length = </em><?php echo $uriLength?></div>
        <?php endif;?>
        <button type="reset" onClick="location.href='<?php echo $_SERVER['SCRIPT_NAME'];?>';" style="float:right;width:150px;height:50px;margin:15px 0;font-size:larger">reset</button>
    </div>
    <textarea placeholder="No data entered" onclick="this.focus();this.select();" style="padding:10px;color:#333;border:1px solid #CCC" cols="75" rows="5" readonly="readonly"><?php echo @$result;?></textarea>


</form>
<div style="float:left">
    <!--LiveInternet counter--><script type="text/javascript"><!--
        document.write("<a href='http://www.liveinternet.ru/click' "+
            "target=_blank><img src='//counter.yadro.ru/hit?t44.1;r"+
            escape(document.referrer)+((typeof(screen)=="undefined")?"":
            ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
            ";"+Math.random()+
            "' alt='' title='LiveInternet' "+
            "border='0' width='31' height='31'><\/a>")
        //--></script><!--/LiveInternet-->
</div>
<div style="float:right;color:#CCC">erobober@gmail.com</div>
</body>
</html>