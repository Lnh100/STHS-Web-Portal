<?php include('Connections/settings.php'); ?>
<?php include("includes/sessionInfo.php") ?>
<?php include("includes/functions.php") ?>
<?php include("includes/langfile.php") ?>
<?php include("includes/langs.php") ?>
<?php include("includes/langs_stats.php") ?>
<?php
switch ($lang){ 
case 'en': 
	$l_note = "The players listed on this page are on the final year of their contract.  If they are not resigned by the end of the year, they will be available on the free agency market.";
	$l_C  = "C";
	$l_LW  = "LW";
	$l_RW  = "RW";
	$l_D  = "D";
	break; 
	
case 'fr': 
	$l_note = "Les joueurs list&eacute;s sur cette page en sont &agrave; la derni&egrave;re ann&eacute;e de leur contrat. S'ils ne sont pas resign&eacute;s avant le 1er juillet, ils deviendront disponibles sur le march&eacute; des agents libres.";
	$l_C  = "C";
	$l_LW  = "AG";
	$l_RW  = "AD";
	$l_D  = "D";
	break; 
} 


$query_GetInfo = sprintf("SELECT UFA, JuniorLeague FROM config");
$GetInfo = mysql_query($query_GetInfo, $connection) or die(mysql_error());
$row_GetInfo = mysql_fetch_assoc($GetInfo);
$UFA=$row_GetInfo['UFA'];

$query_GetSkaters = sprintf("SELECT P.* FROM players as P WHERE P.Number NOT IN (select c.Player FROM playerscontractoffers as c) AND P.Contract=1 AND Retired=0 GROUP BY P.Name ORDER BY P.Salary1 desc, P.Overall desc");
$GetSkaters = mysql_query($query_GetSkaters, $connection) or die(mysql_error());
$row_GetSkaters = mysql_fetch_assoc($GetSkaters);
$totalRows_GetSkaters = mysql_num_rows($GetSkaters);

$query_GetGoalies = sprintf("SELECT G.* FROM goalies as G WHERE G.Number NOT IN (select c.Player FROM playerscontractoffers as c) AND G.Contract=1 AND Retired=0 GROUP BY G.Name ORDER BY G.Salary1 desc, G.Overall desc");
$GetGoalies = mysql_query($query_GetGoalies, $connection) or die(mysql_error());
$row_GetGoalies = mysql_fetch_assoc($GetGoalies);
$totalRows_GetGoalies = mysql_num_rows($GetGoalies);

$query_GetCoach = sprintf("SELECT C.* FROM coaches as C WHERE C.Number NOT IN (select c.Player FROM playerscontractoffers as c) AND C.Contract=1 GROUP BY C.Name ORDER BY C.Salary desc");
$GetCoach = mysql_query($query_GetCoach, $connection) or die(mysql_error());
$row_GetCoach = mysql_fetch_assoc($GetCoach);
$totalRows_GetCoach = mysql_num_rows($GetCoach);


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title><?php echo $l_nav_pending_free_agents;?> - <?php echo $_SESSION['SiteName'] ; ?></title>

<link rel="shortcut icon" type="image/png" href="<?php echo $_SESSION['DomainName']; ?>/image/<?php echo $_SESSION['FavIcon'];?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['DomainName']; ?>/css/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['DomainName']; ?>/css/html5.css">
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['DomainName']; ?>/css/menu.css">
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['DomainName']; ?>/css/jquery.accessible-news-slider.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['DomainName']; ?>/css/jquery-ui-1.8.custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['DomainName']; ?>/css/header.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['DomainName']; ?>/css/tipsy.css">   
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['DomainName']; ?>/css/bubbletip.css" />


<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/jcarousellite_1.0.1c4.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/formly.min.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/jquery.pop.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/jquery.tipsy.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/jquery.tablesorter.min.js"></script>  
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/ui.core.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/reflection.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/jQuery.bubbletip-1.0.6.js"></script>

<?php if(isset($_SESSION['username'])){ ?>
<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['DomainName']; ?>/css/chat.css" />
<script type="text/javascript" src="<?php echo $_SESSION['DomainName']; ?>/js/chat.js"></script>
<?php } ?>

<!--[if lte IE 9]>
<script src="<?php echo $_SESSION['DomainName']; ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<script type="text/javascript">
$(function(){ 
  $("table").tablesorter(); 
  $('#cssdropdown li.headlink').hover(
		function() { $('ul', this).css('display', 'block'); },
		function() { $('ul', this).css('display', 'none'); });
});;
</script>

<style media="all" type="text/css">
#container {background-image:url(<?php echo $_SESSION['DomainName']; ?>/image/headers/<?php echo $_SESSION['current_HeaderImage']; ?>); background-color:#<?php echo $_SESSION['current_PrimaryColor'];?>;}
a {color:#<?php echo $_SESSION['current_PrimaryColor']; ?>;}
table.tablesorter thead tr th { background-color: #<?php echo $_SESSION['current_SecondaryColor']; ?>; color:#<?php echo $_SESSION['current_TextColor']; ?>;}
table.tablesorterRates thead tr th { background-color: #<?php echo $_SESSION['current_SecondaryColor']; ?>; color:#<?php echo $_SESSION['current_TextColor']; ?>;}
table.tablesorter thead tr th a{ color:#<?php echo $_SESSION['current_TextColor']; ?>;}
table.tablesorterRates thead tr th a{ color:#<?php echo $_SESSION['current_TextColor']; ?>;}
footer { background-color:#<?php echo $_SESSION['current_PrimaryColor']; ?>;}
#FatFooter { background-color:#<?php echo $_SESSION['current_SecondaryColor']; ?>; color:#<?php echo $_SESSION['current_TextColor']; ?>;}
<?php if ($_SESSION['current_SecondaryColor'] == $_SESSION['current_PrimaryColor']){ echo "#FatFooter a { color:#".$_SESSION['current_TextColor']."; } "; } ?>
h3 {color:#<?php echo $_SESSION['current_PrimaryColor']; ?>;}
#cssdropdown, #cssdropdown ul {background-color:#<?php echo $_SESSION['current_PrimaryColor']; ?>;}
nav {background-color:#<?php echo $_SESSION['current_PrimaryColor']; ?>;}
</style>
</head>

<body>
<div align="center">
<div id="wrapper">
<?php include("includes/header.php"); ?>
<?php include("includes/nav.php"); ?>

<article>
	<!-- RIGHT HAND SIDE BAR GOES HERE -->
    <!--<aside></aside>-->
    
	<!-- MAIN PAGE CONTENT GOES HERE -->
    <section>
    <h1><?php echo $l_nav_pending_free_agents;?></h1>
	<p><?php echo $l_note;?></p>
    
    
   	<h3><?php echo $l_Skaters;?></h3>
    <table  cellspacing="0" border="0" width="100%" class="tablesorter">
        <thead>
		<tr style="background-color:#<?php echo $_SESSION['current_PrimaryColor']; ?>">
			<th><a title="<?php echo $l_Name;?>"><?php echo $l_Name;?></a></th>
			<th width="80"><a title="<?php echo $l_Positions;?>"><?php echo $l_Positions;?></a></th>
			<th><a title="<?php echo $l_CK_D;?>">CK</a></th>
			<th><a title="<?php echo $l_FG_D;?>">FG</a></th>
			<th><a title="<?php echo $l_DI_D;?>">DI</a></th>	
			<th><a title="<?php echo $l_SK_D;?>">SK</a></th>	
			<th><a title="<?php echo $l_ST_D;?>">ST</a></th>	
			<th><a title="<?php echo $l_EN_D;?>">EN</a></th>	
			<th><a title="<?php echo $l_DU_D;?>">DU</a></th>				
			<th><a title="<?php echo $l_PH_D;?>">PH</a></th>	
			<th><a title="<?php echo $l_FO_D;?>">FO</a></th>	
			<th><a title="<?php echo $l_PA_D;?>">PA</a></th>	
			<th><a title="<?php echo $l_SC_D;?>">SC</a></th>	
			<th><a title="<?php echo $l_DF_D;?>">DF</a></th>	
			<th><a title="<?php echo $l_PenS_D;?>">PS</a></th>	
			<th><a title="<?php echo $l_EX_D;?>">EX</a></th>	
			<th><a title="<?php echo $l_LD_D;?>">LD</a></th>
			<th><a title="<?php echo $l_MO_D;?>">MO</a></th>
			<th><a title="<?php echo $l_PO_D;?>">PO</a></th>	
			<th><a title="<?php echo $l_OV_D;?>">OV</a></th>
			<th><a title="Type">Type</a></th>
            <th><a title="<?php echo $l_Salary;?>">Salary</a></th>
		</tr>
        </thead>
		<tbody>
        <?php do { ?>
		  <tr>
			<td align="left"><a href="player.php?player=<?php echo $row_GetSkaters['Number']; ?>"><?php echo $row_GetSkaters['Name']; ?></a></td>
            <td align="left">
           <?php 
			echo '<div style="display:block; float:left; width:20px; text-align:center; vertical-align:middle">';
			if ($row_GetSkaters['PosC'] == "True" || $row_GetSkaters['PosC'] == "Vrai"){
				echo $l_C;
			} else { echo "&nbsp;"; }
                    echo '</div>';
                    echo '<div style="display:block; float:left; width:20px; text-align:center; vertical-align:middle">';
			if ($row_GetSkaters['PosLW'] == "True" || $row_GetSkaters['PosLW'] == "Vrai"){
				echo $l_LW;
			} else { echo "&nbsp;"; }
                    echo '</div>';
                    echo '<div style="display:block; float:left; width:20px; text-align:center; vertical-align:middle">';
			if ($row_GetSkaters['PosRW'] == "True" || $row_GetSkaters['PosRW'] == "Vrai"){
				echo $l_RW;
			} else { echo "&nbsp;"; }
                    echo '</div>';
                    echo '<div style="display:block; float:left; width:20px; text-align:center; vertical-align:middle">';
			if ($row_GetSkaters['PosD'] == "True" || $row_GetSkaters['PosD'] == "Vrai"){
				echo $l_D;
			} else { echo "&nbsp;"; }
			echo '</div>';
			?>
            </td>
			<td align="center"><?php echo $row_GetSkaters['CK'];?></td>
			<td align="center"><?php echo $row_GetSkaters['FG'];?></td>
			<td align="center"><?php echo $row_GetSkaters['DI'];?></td>
			<td align="center"><?php echo $row_GetSkaters['SK'];?></td>
			<td align="center"><?php echo $row_GetSkaters['ST'];?></td>
			<td align="center"><?php echo $row_GetSkaters['EN'];?></td>
			<td align="center"><?php echo $row_GetSkaters['DU'];?></td>
			<td align="center"><?php echo $row_GetSkaters['PH'];?></td>
			<td align="center"><?php echo $row_GetSkaters['FO'];?></td>
			<td align="center"><?php echo $row_GetSkaters['PA'];?></td>
			<td align="center"><?php echo $row_GetSkaters['SC'];?></td>
			<td align="center"><?php echo $row_GetSkaters['DF'];?></td>
			<td align="center"><?php echo $row_GetSkaters['PS'];?></td>
			<td align="center"><?php echo $row_GetSkaters['EX'];?></td>
			<td align="center"><?php echo $row_GetSkaters['LD'];?></td>			
			<td align="center"><?php echo $row_GetSkaters['MO'];?></td>
			<td align="center"><?php echo $row_GetSkaters['PO'];?></td>
			<td align="center"><?php if ($_SESSION['DisplayOV'] == 1) {  echo $row_GetSkaters['Overall'];} ?></td>
            <td align="center"><?php if(getAge($row_GetSkaters['AgeDate']) > $UFA){ echo "UFA"; } else { echo "RFA"; }?></td>
            <td align="center">$<?php echo number_format($row_GetSkaters['Salary1'],0);?></td>
		  </tr>
		  <?php } while ($row_GetSkaters = mysql_fetch_assoc($GetSkaters)); ?>	
		 </table>
        <br />
		
        <h3><?php echo $l_Goalies;?></h3>
        
		<table cellspacing="0" border="0" width="100%" class="tablesorter">
        <thead>
		<tr style="background-color:#<?php echo $_SESSION['current_PrimaryColor']; ?>">
			<th><a title="<?php echo $l_Name;?>"><?php echo $l_Name;?></a></th>
			<th><a title="<?php echo $l_SK_D;?>">SK</a></th>
			<th><a title="<?php echo $l_DU_D;?>">DU</a></th>
			<th><a title="<?php echo $l_EN_D;?>">EN</a></th>	
			<th><a title="<?php echo $l_SZ_D;?>">SZ</a></th>	
			<th><a title="<?php echo $l_AG_D;?>">AG</a></th>	
			<th><a title="<?php echo $l_RB_D;?>">RB</a></th>	
			<th><a title="<?php echo $l_STC_D;?>">SC</a></th>				
			<th><a title="<?php echo $l_HS_D;?>">HS</a></th>	
			<th><a title="<?php echo $l_RT_D;?>">RT</a></th>	
			<th><a title="<?php echo $l_PC_D;?>">PC</a></th>	
			<th><a title="<?php echo $l_PenS_D;?>">PS</a></th>	
			<th><a title="<?php echo $l_EX_D;?>">EX</a></th>	
			<th><a title="<?php echo $l_LD_D;?>">LD</a></th>
			<th><a title="<?php echo $l_MO_D;?>">MO</a></th>
			<th><a title="<?php echo $l_PO_D;?>">PO</a></th>	
			<th><a title="<?php echo $l_OV_D;?>">OV</a></th>	
			<th><a title="Type">Type</a></th>
            <th><a title="<?php echo $l_Salary;?>">Salary</a></th>
		</tr>
        </thead>
		<tbody>
        <?php do { ?>
		  <tr>
			<td align="left"><a href="goalie.php?player=<?php echo $row_GetGoalies['Number']; ?>"><?php echo $row_GetGoalies['Name']; ?></a></td>
			<td align="center"><?php echo $row_GetGoalies['SK'];?></td>
			<td align="center"><?php echo $row_GetGoalies['DU'];?></td>
			<td align="center"><?php echo $row_GetGoalies['EN'];?></td>
			<td align="center"><?php echo $row_GetGoalies['SZ'];?></td>
			<td align="center"><?php echo $row_GetGoalies['AG'];?></td>
			<td align="center"><?php echo $row_GetGoalies['RB'];?></td>
			<td align="center"><?php echo $row_GetGoalies['SC'];?></td>
			<td align="center"><?php echo $row_GetGoalies['HS'];?></td>
			<td align="center"><?php echo $row_GetGoalies['RT'];?></td>
			<td align="center"><?php echo $row_GetGoalies['PC'];?></td>
			<td align="center"><?php echo $row_GetGoalies['PS'];?></td>
			<td align="center"><?php echo $row_GetGoalies['EX'];?></td>
			<td align="center"><?php echo $row_GetGoalies['LD'];?></td>			
			<td align="center"><?php echo $row_GetGoalies['MO'];?></td>
			<td align="center"><?php echo $row_GetGoalies['PO'];?></td>
			<td align="center"><?php if ($_SESSION['DisplayOV'] == 1) {  echo $row_GetGoalies['Overall'];} ?></td>
            <td align="center"><?php if(getAge($row_GetGoalies['AgeDate']) > $UFA){ echo "UFA"; } else { echo "RFA"; }?></td>
            <td align="center">$<?php echo number_format($row_GetGoalies['Salary1'],0);?></td>
		  </tr>
		  <?php } while ($row_GetGoalies = mysql_fetch_assoc($GetGoalies)); ?>	
        </tbody>
        </table>
        <br />
        
        <h3><?php echo $l_nav_coaches;?></h3>
      
		<table cellspacing="0" border="0" width="100%" class="tablesorter">
        <thead>
		  <tr style="background-color:#<?php echo $_SESSION['current_PrimaryColor']; ?>">
			<th><a title="<?php echo $l_Name;?>"><?php echo $l_Name;?></a></th>
			<th><a title="<?php echo $l_PH_D;?>">PH</a></th>	
			<th><a title="<?php echo $l_DF_D;?>">DF</a></th>	
			<th><a title="<?php echo $l_OF_D;?>">OF</a></th>	
			<th><a title="<?php echo $l_PD_D;?>">PD</a></th>	
			<th><a title="<?php echo $l_EX_D;?>">EX</a></th>	
			<th><a title="<?php echo $l_LD_D;?>">LD</a></th>
			<th><a title="<?php echo $l_PO_D;?>">PO</a></th>
            <th><a title="<?php echo $l_Salary;?>">Salary</a></th>
		  </tr>
        </thead>
		<tbody>
		<?php do { ?>
		  <tr>
			<td align="left"><a href="coach.php?Coach=<?php echo $row_GetCoach['Name']; ?>"><?php echo $row_GetCoach['Name']; ?></a></td>
			<td align="center"><?php echo $row_GetCoach['PH']; ?></td>
			<td align="center"><?php echo $row_GetCoach['DF']; ?></td>
			<td align="center"><?php echo $row_GetCoach['OF']; ?></td>
			<td align="center"><?php echo $row_GetCoach['PD']; ?></td>
			<td align="center"><?php echo $row_GetCoach['EX']; ?></td>
			<td align="center"><?php echo $row_GetCoach['LD']; ?></td>
			<td align="center"><?php echo $row_GetCoach['PO']; ?></td>
            <td align="center">$<?php echo number_format($row_GetCoach['Salary'],0);?></td>
		  </tr>
		<?php } while ($row_GetCoach = mysql_fetch_assoc($GetCoach)); ?>	
        </tbody>
		</table>
        
 	</section>
</article>
 
<?php include("includes/footer.php"); ?>
<?php include("includes/statusBar.php"); ?>
</div>
</div>
</body>
</html>
