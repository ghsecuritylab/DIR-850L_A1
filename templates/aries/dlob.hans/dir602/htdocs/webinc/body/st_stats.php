<?
include "/htdocs/phplib/xnode.php";
include "/htdocs/webinc/config.php";

function get_runtime_eth_path($uid)
{
	$p = XNODE_getpathbytarget("", "inf", "uid", $uid, 0);
	if($p == "") return $p;

	return XNODE_getpathbytarget("/runtime", "phyinf", "uid", query($p."/phyinf"));
}
function get_runtime_wifi_path($uid)
{
	$p = XNODE_getpathbytarget("", "phyinf", "wifi", $uid);
	if($p == "") return $p;
	return XNODE_getpathbytarget("/runtime", "phyinf", "uid", query($p."/uid"));
}

/* Do RESET count */
$tx = "/stats/tx/packets";
$rx = "/stats/rx/packets";
$tx_bytes = "/stats/tx/bytes";
$rx_bytes = "/stats/rx/bytes";
$tx_drop = "/stats/tx/drop";
$rx_drop = "/stats/rx/drop";
$tx_error = "/stats/tx/error";
$rx_error = "/stats/rx/error";
$collision = "/stats/tx/collision";

/*base node*/
$ltxp= "/stats/last_tx_pkt_cnt";
$lrxp= "/stats/last_rx_pkt_cnt";
$ltxd= "/stats/last_tx_drop_cnt";
$lrxd= "/stats/last_rx_drop_cnt";
$ltxe= "/stats/last_tx_error_cnt";
$lrxe= "/stats/last_rx_error_cnt";
$ltxc= "/stats/last_collision_cnt";

if ($_POST["act"]!="")
{
	$p = get_runtime_eth_path("WAN-1");	
	if ($p != "")
	{
		$cnt=query($p.$tx);
		set($p."/stats/last_tx_pkt_cnt",$cnt);

		$cnt=query($p.$rx);
		set($p."/stats/last_rx_pkt_cnt",$cnt);

		$cnt=query($p.$tx_drop);
		set($p."/stats/last_tx_drop_cnt",$cnt);

		$cnt=query($p.$rx_drop);
		set($p."/stats/last_rx_drop_cnt",$cnt);

		$cnt=query($p.$tx_error);
		set($p."/stats/last_tx_error_cnt",$cnt);

		$cnt=query($p.$rx_error);
		set($p."/stats/last_rx_error_cnt",$cnt);

		$cnt=query($p.$collision);
		set($p."/stats/last_collision_cnt",$cnt);
		
		set($p."/stats/reset", "dummy");
	}

	$p = get_runtime_eth_path("LAN-1");	
	if ($p != "")
	{
		$cnt=query($p.$tx);
		set($p."/stats/last_tx_pkt_cnt",$cnt);

		$cnt=query($p.$rx);
		set($p."/stats/last_rx_pkt_cnt",$cnt);

		$cnt=query($p.$tx_drop);
		set($p."/stats/last_tx_drop_cnt",$cnt);

		$cnt=query($p.$rx_drop);
		set($p."/stats/last_rx_drop_cnt",$cnt);

		$cnt=query($p.$tx_error);
		set($p."/stats/last_tx_error_cnt",$cnt);

		$cnt=query($p.$rx_error);
		set($p."/stats/last_rx_error_cnt",$cnt);

		$cnt=query($p.$collision);
		set($p."/stats/last_collision_cnt",$cnt);
		
		set($p."/stats/reset", "dummy");
	}

	foreach ("/phyinf")
	{
		if (query("type")!="wifi") continue;
		$uid = query("uid");
		$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $uid);

		if ($p == "") continue;
		if ($uid == $WLAN1 || $uid == $WLAN1_GZ || $uid == $WLAN2 || $uid == $WLAN2_GZ)
		{
			$cnt=query($p.$tx);
			set($p."/stats/last_tx_pkt_cnt",$cnt);

			$cnt=query($p.$rx);
			set($p."/stats/last_rx_pkt_cnt",$cnt);

			$cnt=query($p.$tx_drop);
			set($p."/stats/last_tx_drop_cnt",$cnt);

			$cnt=query($p.$rx_drop);
			set($p."/stats/last_rx_drop_cnt",$cnt);

			$cnt=query($p.$tx_error);
			set($p."/stats/last_tx_error_cnt",$cnt);

			$cnt=query($p.$rx_error);
			set($p."/stats/last_rx_error_cnt",$cnt);

			$cnt=query($p.$collision);
			set($p."/stats/last_collision_cnt",$cnt);
			
			set($p."/stats/reset", "dummy");
		}
	}
}

/*
function update_statistic($p)
{
	if ($p != "") 
	{
		$cnt=query($p.$tx);
		$cnt=$cnt+query($p."/stats/last_tx_pkt_cnt");
		set($p."/stats/last_tx_pkt_cnt",$cnt);

		$cnt=query($p.$rx);
		$cnt=$cnt+query($p."/stats/last_rx_pkt_cnt");
		set($p."/stats/last_rx_pkt_cnt",$cnt);

		$cnt=query($p.$tx_drop);
		$cnt=$cnt+query($p."/stats/last_tx_drop_cnt");
		set($p."/stats/last_tx_drop_cnt",$cnt);

		$cnt=query($p.$rx_drop);
		$cnt=$cnt+query($p."/stats/last_rx_drop_cnt");
		set($p."/stats/last_rx_drop_cnt",$cnt);

		$cnt=query($p.$tx_error);
		$cnt=$cnt+query($p."/stats/last_tx_error_cnt");
		set($p."/stats/last_tx_error_cnt",$cnt);

		$cnt=query($p.$rx_error);
		$cnt=$cnt+query($p."/stats/last_rx_error_cnt");
		set($p."/stats/last_rx_error_cnt",$cnt);

		$cnt=query($p.$collision);
		$cnt=$cnt+query($p."/stats/last_collision_cnt");
		set($p."/stats/last_collision_cnt",$cnt);

		setattr($p.$tx_drop,"get","scut -p ".query($p."/name").": -f 12 /proc/net/dev -b ".query($p."/stats/last_tx_drop_cnt")." "); 
		setattr($p.$rx_drop,"get","scut -p ".query($p."/name").": -f 4 /proc/net/dev -b ".query($p."/stats/last_rx_drop_cnt")." "); 

		setattr($p.$tx_error,"get","scut -p ".query($p."/name").": -f 11 /proc/net/dev -b ".query($p."/stats/last_tx_error_cnt")." "); 
		setattr($p.$rx_error,"get","scut -p ".query($p."/name").": -f 3 /proc/net/dev -b ".query($p."/stats/last_rx_error_cnt")." "); 

		setattr($p.$collision,"get","scut -p ".query($p."/name").": -f 14 /proc/net/dev -b ".query($p."/stats/last_collision_cnt")." ");

		setattr($p."/stats/rx/packets","get","scut -p ".query($p."/name").": -f 2 /proc/net/dev -b ".query($p."/stats/last_rx_pkt_cnt")." "); 
		setattr($p."/stats/tx/packets","get","scut -p ".query($p."/name").": -f 10 /proc/net/dev -b ".query($p."/stats/last_tx_pkt_cnt")." "); 
		set($p."/stats/reset", "dummy");
	}
}
*/

/* we reset the counters and get them immediately here, it may has the synchronization issue. */
/* Do RESET count */
/*
if ($_POST["act"]!="")
{
	$p = get_runtime_eth_path("WAN-1");	
	if ($p != "")
	{
		update_statistic($p);
	}
	$p = get_runtime_eth_path("LAN-1");	
	if ($p != "")
	{
		update_statistic($p);
	}
	foreach ("/phyinf")
	{
		if (query("type")!="wifi") continue;
		$uid = query("uid");
		$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $uid);

		if ($p == "") continue;
		if ($uid == $WLAN1 || $uid == $WLAN1_GZ || $uid == $WLAN2 || $uid == $WLAN2_GZ)
		{
			update_statistic($p);
		}
	}
}
*/

$p = get_runtime_eth_path("WAN-1");
if ($p == "")
{	
	$wan1_tx = i18n("N/A");						$wan1_rx = i18n("N/A"); 
	$wan1_txdrop=i18n("N/A");					$wan1_rxdrop=i18n("N/A");
	$wan1_collision=i18n("N/A"); 				$wan1_error=i18n("N/A");
}
else		 
{
	/*$tx_cnt = query($p.$tx); if ($tx_cnt == "") $tx_cnt = "0";
	$rx_cnt = query($p.$rx); if ($rx_cnt == "") $rx_cnt = "0";
	$wan1_tx = cut($tx_cnt,0," ");	$wan1_rx = cut($rx_cnt,0," ");
	
	$tx_cnt = query($p.$tx_bytes); if ($tx_cnt == "") $tx_cnt = "0";
	$rx_cnt = query($p.$rx_bytes); if ($rx_cnt == "") $rx_cnt = "0";
	$wan1_txbytes = cut($tx_cnt,0," ");	$wan1_rxbytes = cut($rx_cnt,0," ");
	
	$tx_cnt = query($p.$tx_drop); if ($tx_cnt == "") $tx_cnt = "0";
	$rx_cnt = query($p.$rx_drop); if ($rx_cnt == "") $rx_cnt = "0";
	$wan1_txdrop = cut($tx_cnt,0," ");	$wan1_rxdrop = cut($rx_cnt,0," ");	
	
	$tx_cnt = query($p.$collision); if ($tx_cnt == "") $tx_cnt = "0";
	$wan1_collision = cut($tx_cnt,0," ");		
	
	$tx_cnt = query($p.$tx_error); if ($tx_cnt == "") $tx_cnt = "0";
	$rx_cnt = query($p.$rx_error); if ($rx_cnt == "") $rx_cnt = "0";
	$tmp_txerror = cut($tx_cnt,0," ");	$tmp_rxerror = cut($rx_cnt,0," ");
	$wan1_error = cut($tmp_txerror + $tmp_rxerror,0," ");*/

	$wan1_tx = query($p.$tx)-query($p.$ltxp);	$wan1_rx = query($p.$rx)-query($p.$lrxp);
	$wan1_txdrop = query($p.$tx_drop)-query($p.$ltxd);	$wan1_rxdrop = query($p.$rx_drop)-query($p.$lrxd);
	$tmp_txerror = query($p.$tx_error)-query($p.$ltxe);	$tmp_rxerror = query($p.$rx_error)-query($p.$lrxe);
	$wan1_collision = query($p.$collision)-query($p.$ltxc);	$wan1_error = $tmp_txerror + $tmp_rxerror;
}

$p = get_runtime_eth_path("LAN-1");
if ($p == "")
{
	$lan1_tx = i18n("N/A");						$lan1_rx = i18n("N/A");
	$lan1_txdrop=i18n("N/A");					$lan1_rxdrop=i18n("N/A");
	$lan1_collision=i18n("N/A"); 				$lan1_error=i18n("N/A");
}
else		 
{
	$lan1_tx = query($p.$tx)-query($p.$ltxp);	$lan1_rx = query($p.$rx)-query($p.$lrxp);
	$lan1_txdrop = query($p.$tx_drop)-query($p.$ltxd);	$lan1_rxdrop = query($p.$rx_drop)-query($p.$lrxd);
	$tmp_txerror = query($p.$tx_error)-query($p.$ltxe);	$tmp_rxerror = query($p.$rx_error)-query($p.$lrxe);
	$lan1_collision = query($p.$collision)-query($p.$ltxc);	$lan1_error = $tmp_txerror + $tmp_rxerror;
}

$wifi2g_tx = i18n("N/A");					$wifi2g_rx = i18n("N/A");
$wifi2g_txdrop=i18n("N/A");					$wifi2g_rxdrop=i18n("N/A");
$wifi2g_collision=i18n("N/A"); 				$wifi2g_error=i18n("N/A");

$wifi5g_tx = i18n("N/A");					$wifi5g_rx = i18n("N/A");
$wifi5g_txdrop=i18n("N/A");					$wifi5g_rxdrop=i18n("N/A");
$wifi5g_collision=i18n("N/A"); 				$wifi5g_error=i18n("N/A");

foreach ("/phyinf")
{
	if (query("type")!="wifi") continue;
	$uid = query("uid");
	$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $uid);

	if ($p == "") continue;
	if ($uid == $WLAN1 || $uid == $WLAN1_GZ)
	{
		if (isdigit($wifi2g_tx) == 0)
		{
			$wifi2g_tx="0";						$wifi2g_rx="0";
			$wifi2g_txdrop="0";					$wifi2g_rxdrop="0";
			$wifi2g_collision="0"; 				$wifi2g_error="0";
		}
		/*$wifi2g_tx += query($p.$tx);
		$wifi2g_rx += query($p.$rx);
		$wifi2g_txdrop += query($p.$tx_drop);	$wifi2g_rxdrop += query($p.$rx_drop);
		$wifi2g_collision += query($p.$collision);
		$tmp_txerror=query($p.$tx_error);
		$tmp_rxerror=query($p.$rx_error);
		$wifi2g_error += $tmp_txerror+$tmp_rxerror;*/

		$wifi2g_tx = query($p.$tx)-query($p.$ltxp);	$wifi2g_rx = query($p.$rx)-query($p.$lrxp);
		$wifi2g_txdrop = query($p.$tx_drop)-query($p.$ltxd);	$wifi2g_rxdrop = query($p.$rx_drop)-query($p.$lrxd);
		$tmp_txerror = query($p.$tx_error)-query($p.$ltxe);	$tmp_rxerror = query($p.$rx_error)-query($p.$lrxe);
		$wifi2g_collision = query($p.$collision)-query($p.$ltxc);	$wifi2g_error = $tmp_txerror + $tmp_rxerror;
	}
	else if ($uid == $WLAN2 || $uid == $WLAN2_GZ)
	{
		if (isdigit($wifi5g_tx) == 0)
		{
			$wifi5g_tx="0";						$wifi5g_rx="0";
			$wifi5g_txdrop="0";					$wifi5g_rxdrop="0";
			$wifi5g_collision="0"; 				$wifi5g_error="0";
		}
		/*$wifi5g_tx += query($p.$tx);	$wifi5g_rx += query($p.$rx);
		$wifi5g_txdrop += query($p.$tx_drop);	$wifi5g_rxdrop += query($p.$rx_drop);
		$wifi5g_collision += query($p.$collision);
		$tmp_txerror=query($p.$tx_error);
		$tmp_rxerror=query($p.$rx_error);
		$wifi5g_error += $tmp_txerror+$tmp_rxerror;*/

		$wifi5g_tx = query($p.$tx)-query($p.$ltxp);	$wifi5g_rx = query($p.$rx)-query($p.$lrxp);
		$wifi5g_txdrop = query($p.$tx_drop)-query($p.$ltxd);	$wifi5g_rxdrop = query($p.$rx_drop)-query($p.$lrxd);
		$tmp_txerror = query($p.$tx_error)-query($p.$ltxe);	$tmp_rxerror = query($p.$rx_error)-query($p.$lrxe);
		$wifi5g_collision = query($p.$collision)-query($p.$ltxc);	$wifi5g_error = $tmp_txerror + $tmp_rxerror;
	}
	else
	{
		continue;
	}	
}

?>
<div class="orangebox">
	<h1><?echo i18n("Traffic Statistics");?></h1>
	<p><?
		echo i18n("Traffic Statistics displays Receive and Transmit packets passing through the device.");
	?></p>
	<form id="mainform" name="resetcount" action="<?=$TEMP_MYNAME?>.php" method="POST">
		<p>
			<input type="button" value="<?echo i18n("Refresh Statistics");?>" onclick="(function(){self.location='<?=$TEMP_MYNAME?>.php?r='+COMM_RandomStr(5);})();">&nbsp;
			<input type="submit" name="act" value="<?echo i18n("Reset Statistics");?>">
		</p>
	</form>
</div>
<div class="blackbox">
	<h2><?echo i18n("LAN Statistics");?></h2>
	<div class="centerline" align="center">
		<table borderColor=#ffffff cellSpacing=1 cellPadding=1 width=525>
			<tr>
				<td ><div class="duple"><?echo i18n("Sent ");?>:</div></td>
				<td width=340><?=$lan1_tx?></td>
				<td ><div class="duple"><?echo i18n("Received ");?>:</div></td>
				<td width=340><?=$lan1_rx?></td>
			</tr>
			<tr>
				<td ><div class="duple"><?echo i18n("TX Packets Dropped ");?>:</div></td>
				<td width=340><?=$lan1_txdrop?></td>
				<td ><div class="duple"><?echo i18n("RX Packets Dropped ");?>:</div></td>
				<td width=340><?=$lan1_rxdrop?></td>
			</tr>

			<tr>
				<td ><div class="duple"><?echo i18n("Collisions ");?>:</div></td>
				<td width=340><?=$lan1_collision?></td>
				<td ><div class="duple"><?echo i18n("Errors ");?>:</div></td>
				<td width=340><?=$lan1_error?></td>
			</tr>
		</table>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("WAN Statistics");?></h2>
	<div class="centerline" align="center">
		<table borderColor=#ffffff cellSpacing=1 cellPadding=1 width=525>
			<tr>
				<td ><div class="duple"><?echo i18n("Sent ");?>:</div></td>
				<td width=340><?=$wan1_tx?></td>
				<td ><div class="duple"><?echo i18n("Received ");?>:</div></td>
				<td width=340><?=$wan1_rx?></td>
			</tr>
			<tr>
				<td ><div class="duple"><?echo i18n("TX Packets Dropped ");?>:</div></td>
				<td width=340><?=$wan1_txdrop?></td>
				<td ><div class="duple"><?echo i18n("RX Packets Dropped ");?>:</div></td>
				<td width=340><?=$wan1_rxdrop?></td>
			</tr>

			<tr>
				<td ><div class="duple"><?echo i18n("Collisions ");?>:</div></td>
				<td width=340><?=$wan1_collision?></td>
				<td ><div class="duple"><?echo i18n("Errors ");?>:</div></td>
				<td width=340><?=$wan1_error?></td>
			</tr>
		</table>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("WIRELESS Statistics - 2.4GHz Band");?></h2>
	<div class="centerline" align="center">
		<table borderColor=#ffffff cellSpacing=1 cellPadding=1 width=525>
			<tr>
				<td ><div class="duple"><?echo i18n("Sent ");?>:</div></td>
				<td width=340><?=$wifi2g_tx?></td>
				<td ><div class="duple"><?echo i18n("Received ");?>:</div></td>
				<td width=340><?=$wifi2g_rx?></td>
			</tr>
			<tr>
				<td ><div class="duple"><?echo i18n("TX Packets Dropped ");?>:</div></td>
				<td width=340><?=$wifi2g_txdrop?></td>
				<td ><div class="duple"><?echo i18n("RX Packets Dropped ");?>:</div></td>
				<td width=340><?=$wifi2g_rxdrop?></td>
			</tr>

			<tr>
				<td ><div class="duple"><?echo i18n("Collisions ");?>:</div></td>
				<td width=340><?=$wifi2g_collision?></td>
				<td ><div class="duple"><?echo i18n("Errors ");?>:</div></td>
				<td width=340><?=$wifi2g_error?></td>
			</tr>
		</table>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox" <?if ($FEATURE_DUAL_BAND!="1") echo 'style="display:none;"';?>>
	<h2><?echo i18n("WIRELESS Statistics - 5GHz Band");?></h2>
	<div class="centerline" align="center">
		<table borderColor=#ffffff cellSpacing=1 cellPadding=1 width=525>
			<tr>
				<td ><div class="duple"><?echo i18n("Sent ");?>:</div></td>
				<td width=340><?=$wifi5g_tx?></td>
				<td ><div class="duple"><?echo i18n("Received ");?>:</div></td>
				<td width=340><?=$wifi5g_rx?></td>
			</tr>
			<tr>
				<td ><div class="duple"><?echo i18n("TX Packets Dropped ");?>:</div></td>
				<td width=340><?=$wifi5g_txdrop?></td>
				<td ><div class="duple"><?echo i18n("RX Packets Dropped ");?>:</div></td>
				<td width=340><?=$wifi5g_rxdrop?></td>
			</tr>

			<tr>
				<td ><div class="duple"><?echo i18n("Collisions ");?>:</div></td>
				<td width=340><?=$wifi5g_collision?></td>
				<td ><div class="duple"><?echo i18n("Errors ");?>:</div></td>
				<td width=340><?=$wifi5g_error?></td>
			</tr>
		</table>
	</div>
	<div class="gap"></div>
</div>
