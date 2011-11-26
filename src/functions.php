<?php
/**
 * @author GiAr - papas.evagelos@gmail.com
 * @copyright 2009
 *
 */
function presendResults($ap = array())
{
    $ar='';
    foreach($ap as $k => $v)
    {
        $ar.= strtolower($k . "=" . urldecode($v) . "&");
    }
    return $ar;
}

function getRanID($len)
{
	$pool="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$lchr=strlen($pool)-1;
	$ranid="";
	for($i=0;$i<$len;$i++)	$ranid.=$pool[mt_rand(0,$lchr)];
	return $ranid;
}

function redirect($url)
{
	?>	
			<script language="JavaScript">
				document.location="<?=$url?>";
			</script>
			 
	<?php
	exit;
}

function getAGO($time)
{
	$ago=time()-$time;
	if($ago<60) $ago=$ago." sec";
	elseif($ago<3600) $ago=(int)($ago/60)." min";
	elseif($ago<3600*24) $ago=(int)($ago/3600)." hrs ".(int)(($ago%3600)/60)." min";
	else $ago=date("M d, Y",$time);
	return $ago;
}

function clearForInput($string)
{
    // Replace other special chars
    $specialCharacters = array(
    '#' => '',
    '%' => '',
    '&' => '',
    "\'" => '',
    "\"" => '',
    "  " => '',
    "" => '0'
    );

    trim($string);
    while (list($character, $replacement) = each($specialCharacters))
    {
        $string = str_replace($character, '-' . $replacement . '-', $string);
    }

    $string = strtr($string,
    "ÀÁÂÃÄÅ�áâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
    "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"
    );

    // Remove all remaining other unknown characters
    $string = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $string);
//    $string = preg_replace('/^[\-]+/', '', $string);
//    $string = preg_replace('/[\-]+$/', '', $string);
//    $string = preg_replace('/[\-]{2,}/', ' ', $string);

    return $string;
}

function getDateTime($time)
{
	if(date("d-m-Y",$time)==date("d-m-Y"))
    {
        return "Today, ".date("G:i",$time);
    }
	elseif(date("d-m-Y",$time+24*3600)==date("d-m-Y"))
    {
        return "Yesterday, ".date("G:i",$time);
    }
	elseif(date("d-m-Y",$time-24*3600)==date("d-m-Y"))
    {
        return "Tomorrow, ".date("G:i",$time);
    }
	else
    {
        return date("M d, G:i",$time);
    }
}

function HostURL($host="")
{
	global $_SERVER;
	$path=substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],"/")+1);
	if(empty($host))
	{
		//if(getenv('HTTPS') == 'on') return "http://".$_SERVER['HTTP_HOST'].$path;
		//else 
		return "http://".$_SERVER['HTTP_HOST'].$path;
	}
	else  return $host.$_SERVER['HTTP_HOST'].$path;
}

function HostPath()
{
	global $_SERVER;
	$path=substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],"\\")+1);
	return $path;
}
?>