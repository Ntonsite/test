<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<HTML>
<HEAD>
<TITLE>ARV menu</TITLE>
<meta name="Description" content="Hospital and Healthcare Integrated Information System - CARE2x">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="javascript" >
<!--
function openCreditsWindow() {

	urlholder="../../language/$lang/$lang_credits.php?lang=$lang";
	creditswin=window.open(urlholder,"creditswin","width=500,height=600,menubar=no,resizable=yes,scrollbars=yes");

}

function gethelp(x,s,x1,x2,x3,x4)
{
	if (!x) x="";
	urlholder="../../main/help-router.php<?php echo URL_APPEND; ?>&helpidx="+x+"&src="+s+"&x1="+x1+"&x2="+x2+"&x3="+x3+"&x4="+x4;
	helpwin=window.open(urlholder,"helpwin","width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
	window.helpwin.moveTo(0,0);
}

//-->
</script>
<link rel="stylesheet" href="../../css/themes/default/default.css" type="text/css">
<script language="javascript" src="../../js/hilitebu.js"></script>

<STYLE TYPE="text/css">
A:link  {color: #000066;}
A:hover {color: #cc0033;}
A:active {color: #cc0000;}
A:visited {color: #000066;}
A:visited:active {color: #cc0000;}
A:visited:hover {color: #cc0033;}
</style>
<?php
	if (ARV_case::is_arv_admitted($_GET['pid']))
	{$mode="edit";}
	else {$mode="new";}
?>
</HEAD>
<BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066  >
<table width=100% border=0 cellspacing=0 height=100%>
	<tbody class="main">
		<tr>
			<td  valign="top" align="middle" height="35">
				<table cellspacing="0"  class="titlebar" border=0>
 					<tr valign=top  class="titlebar" >
  						<td bgcolor="#99ccff" >&nbsp;&nbsp;<font color="#330066">EYE CLINIC</font>
       					</td>
  						<td bgcolor="#99ccff" align=right><a
  							 href="javascript:gethelp('arv_menu.php','ARV Menu','<?php echo $mode; ?>')"><img src="../../gui/img/control/blue_aqua/en/en_hilfe-r.gif" border=0 width="76" height="21" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a><a
  							 href="<?php echo $root_path.$breakfile.URL_APPEND.$add_breakfile; ?>" ><img src="../../gui/img/control/blue_aqua/en/en_cancel.gif" border=0 width="76" height="21" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>  </td>
 					</tr>
 				</table>
 			</td>
		</tr>
		<tr>
		<td bgcolor=#ffffff valign=top>
			<blockquote>
			<TABLE cellSpacing=0  width=600 class="submenu_frame" cellpadding="0">
				<TBODY>
					<TR>
						<TD>
							<TABLE cellSpacing=1 cellPadding=3 width=600>

    							<TBODY class="submenu">
      								<TR>
						        		<td align=center><img src="../../gui/img/common/default/showdata.gif" border=0></td>
						        		<TD class="submenu_item"><nobr><a href="eye_examination.php<?php echo URL_APPEND ?>&pid=<?php echo $_GET['pid']?>&mode=new" >Eye Examination</a></nobr></TD>
						        		<TD>Eye Examination Details</TD>
      								</tr>
      								<TR>
						        		<td align=center><img src="../../gui/img/common/default/showdata.gif" border=0></td>
						        		<TD class="submenu_item"><nobr><a href="eye_history.php<?php echo URL_APPEND ?>&pid=<?php echo $_GET['pid']?>&mode=new" >History Sheet</a></nobr></TD>
						        		<TD>Patient History Sheet</TD>
      								</tr>

      								<TR>
						        		<td align=center><img src="../../gui/img/common/default/showdata.gif" border=0></td>
						        		<TD class="submenu_item"><nobr><a href="eye_examination_report.php<?php echo URL_APPEND ?>&pid=<?php echo $_GET['pid']?>&mode=new" >History Report</a></nobr></TD>
						        		<TD>Patient History Report</TD>
      								</tr>

   	 							</TBODY>

                  			</TABLE>
						</TD>
					</TR>
				</TBODY>
			</TABLE>
			<p>
			<p>
			</blockquote>
		</td>
	</tr>
		<tr valign=top >
			<td bgcolor=#cccccc>
				<?php require($root_path.'include/inc_load_copyrite.php');?>
			</td>
		</tr>
	</tbody>
</table>
</BODY>
</HTML>
