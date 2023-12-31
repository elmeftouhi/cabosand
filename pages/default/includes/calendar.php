<?php 

if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$core = $_SESSION["CORE"];
require_once($core."Calendar.php"); 
$months = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

require_once($core."Complexe.php");
$complexes = $complexe->find("",array("conditions"=>array("status="=>1),"order"=>"name"),"v_complexe");

require_once($core."Contrat.php");

?>



<div class="row">
	<div class="col_12">
		<div class="calendar-container">
			<div class="calendar-header">
				<div style="flex-direction: row; display: flex">
					<div style="flex: 1; text-align: right">

						<div style="flex-direction: row; display: flex" class="">
							<div style="display: table-cell; margin-right: 7px" class="">
								<div class="btn-group calendar">
									<a style="padding: 12px 12px" data-counter="0" class="cl_refresh" title="Ajourd'hui"><i class="fas fa-sync-alt"></i> </a>
								</div>											
							</div>				
							<div style="display: table-cell; margin-right: 7px" class="">
								<div class="btn-group calendar">
									<a style="padding: 12px 12px" class="direction" data-action="preview" data-counter="0" title="Précédent"><i class="fa fa-chevron-left"></i></a>
									<a style="padding: 12px 12px" class="direction" data-action="next" data-counter="0"  title="Suivant"><i class="fa fa-chevron-right"></i></a>
								</div>											
							</div>
							<div style="display: table-cell; margin-right: 7px" class="">
								<div class="btn-group calendar">
									<a style="padding: 12px 12px"><i class="far fa-calendar-alt"></i> <span class="calendar_current_interval tohide"><?= date("M") . " " . date("Y") ?></span></a>
								</div>											
							</div>	
							<div style="display: table-cell; margin-right: 7px" class="">
								<div class="btn-group style">
								<a style="padding: 12px 12px" class="selected" data-style="month_">Par Mois</a>
									<a style="padding: 12px 12px" data-style="month">Par Société</a>

								</div>								
							</div>	

							<div style="display: table-cell; margin-right: 7px" class="">
								<select id="id_complexe" style="padding: 8px 5px 9px 5px">
									<option selected value="-1">-- Complexe --</option>
									<?php foreach($complexes as $k=>$v) echo "<option value='".$v["id"]."'>".$v["name"]." <span style='font-weight:bold; font-size:24px'>( " . $v["nbr_propriete"] . " )</span></option>"; ?>
								</select>							
							</div>

							<div style="display: table-cell;" class="contrat">
								<select id="UID" style="padding: 8px 5px 9px 5px">
									<option selected value="-1">-- Client --</option>
								</select>							
							</div>

						</div>


					</div>	

				</div>			
			</div>

			<div class="calendar-body">
			<?php



	$current_month = date('m');
	$current_year = date('Y');

	$options = array(	"counter"		=>	0,
						"style"			=>	(isset($_POST["style"]))? $_POST["style"]:"month_"
					);



	//$_data = $contrat->find("" , array("conditions OR"=>array("")),"v_contrat_periode");
	$request = "
	select * 
	from v_contrat_periode 
	where 
		(year(date_debut)=".$current_year." and month(date_debut)=" . intval($current_month) .") 
	OR 	(year(date_fin)=".$current_year." and month(date_fin)=" . intval($current_month) .") 
	order by date_debut, date_fin";

	$_data = $contrat->execute($request);

	//$_data = $contrat->fetchAll("v_contrat_periode");		

	$data = array();
	foreach($_data as $k=>$v){
		array_push($data, array(
			"societe_name"	=>	$v["societe_name"],
			"date_debut"	=>	$v["date_debut"],
			"date_fin"		=>	$v["date_fin"],
			"color"			=>	$v["color"]
		));
	}


	$args = array("options"=>$options,"data"=>$data);

	echo $calendar->drawCalendar(($current_month),$current_year, $args);

				?>
			</div>


		</div>
	</div>
</div>

<div class="border bg-gray-100 shadow">
	somthing here
</div>

<div class="debug"></div>


