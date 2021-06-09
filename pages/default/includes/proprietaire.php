<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$core = $_SESSION["CORE"];
$table_name = "Proprietaire";
require_once($core.$table_name.".php");  
$ob = new $table_name();
?>

	<div class="row page_title">
		<div class="col_6-inline icon">
			Propriétaire(s)
		</div>
		<div class="col_6-inline actions">
			<button class="btn btn-green add" value="<?= $table_name ?>"><i class="fas fa-plus" aria-hidden="true"></i></button>
			<button class="btn btn-default refresh" value="<?= $table_name ?>"><i class="fas fa-sync-alt"></i></button>
			<button class="btn btn-orange showSearchBar"><i class="fas fa-search-plus"></i></button>
		</div>
	</div>
	<hr>
	<div class="row searchBar hide" style="background-color: rgba(241,241,241,1.00); padding: 10px 0; margin: 10px 0px">
		<div class="col_12-inline">
			
			<div class="input-group" style="overflow: hidden">
				<input type="text" placeholder="Chercher" class="suf" name="" id="request">
				<div class="input-suf"><button title="Chercher" id="a_u_s"><i class="fa fa-search"></i></button></div>
			</div>

		</div>
		<div class="col_5-inline">

		</div>
	</div>
	
	<div class="row <?= strtolower($table_name) ?>">
		<div class="col_12" style="padding: 0">	
		<?php $values = $ob->find(null,array("order"=>"name ASC","limit"=>array(0,20)),"v_proprietaire");  
			$values = (is_null($values)? array(): $values);
			$columns = $ob->getColumns(); 
			$totalItems = $ob->getTotalItems();
			?>

		<div style="display: flex; flex-direction: row; margin-bottom: 8px">

			<div style="flex: auto; padding: 12px 0 10px 5px; margin: 0; color: rgba(118,17,18,1.00)">
			Total : <?= count($values).' / '.$totalItems ?> <span class="current hide">0</span>

			</div>

			<div style="width: 10rem">

				<div style="flex-direction: row; display: flex">
					<div style="flex: 1">
						<select id="showPerPage" style="width: 70px">
							<option value="20" selected>20</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
							<option value="500">500</option>
						</select>
						<span class="hide desc" id="sort_by">name</span>
					</div>
					<div style="flex: 1; text-align: center">
						<div class="btn-group">
							<a style="padding: 12px 12px" id="btn_passive_preview"  title="Précédent"><i class="fa fa-chevron-left"></i></a>
							<a style="padding: 12px 12px" id="btn_passive_next" title="Suivant"><i class="fa fa-chevron-right"></i></a>
						</div>											
					</div>
				</div>

			</div>

		</div>
		<div class="panel" style="overflow: auto;">
				<div class="panel-content" style="padding: 0">
					<table class="table">
						<thead>
							<tr>
								<?php
									$remove_sort = array("contact", "actions");
									foreach($columns as $key=>$value){
										
										$style = ""; //(isset($value["style"]))? $value["style"] : "";
										$is_sort = ( in_array($value["column"], $remove_sort) )? "" : "sort_by";
										$is_display = ( isset($value["display"]) )? "hide" : "";
										
										echo "<th class='" . $is_sort . " " . $is_display . "' data-sort='" . $value["column"] . "' style='".$style."'>" . $value["label"] . "</th>";
										
									}
								?>
							</tr>
						</thead>
						<tbody>
							<?php
								//var_dump($values);
								$content = '<div class="info info-success"><div class="info-success-icon"><i class="fa fa-info" aria-hidden="true"></i> </div><div class="info-message">Liste vide ...</div></div>';
								$i = 0;
								foreach($values as $k=>$v){
									//$status = '<img style="cursor: pointer;" src="http://'.$_SESSION["HOST"].'templates/default/images/disable.png" class="enable_this_c" title="'.$v["id"].'">';
							?>
							<tr class="edit_ligne" data-page="<?= $table_name ?>">
								<?php
																
									foreach($columns as $key=>$value){
										$style = (isset($columns[$key]["style"]))? $columns[$key]["style"]:"";
										
										if(isset($v[ $columns[$key]["column"] ])){
											if($columns[$key]["column"] == "id"){
												echo "<td style='".$style."'><span class='id-ligne'>" . $v[ $columns[$key]["column"] ] . "</span></td>";
											}elseif($columns[$key]["column"] == "name"){
												if($v["notes"] !== ""){
													echo "<td style='".$style."'>" . $v[ $columns[$key]["column"] ] . " <span style='color:blue; font-size:12px'><i class='fas fa-info-circle'></i></span></td>";
												}else{
													echo "<td style='".$style."'>" . $v[ $columns[$key]["column"] ] . "</td>";
												}
											}elseif($columns[$key]["column"] == "status"){
												if($v[ $columns[$key]["column"] ] == 1){
													echo "<td style='".$style."'><div class='label label-green'>Activé</div></td>";
												}else{
													echo "<td style='".$style."'><div class='label label-red'>Activé</div></td>";
												}
											}elseif($columns[$key]["column"] == "nbr_propriete"){
												echo  "<td style='".$style."'><span style='font-size:13px; font-weight:bold; color:blue; text-align:center'>".$v['nbr_propriete']."</span></td>";
											}else{
												echo "<td style='".$style."'>" . $v[ $columns[$key]["column"] ] . "</td>";
											}											
										}else{
											if($columns[$key]["column"] == "contact"){
												echo  "<td style='".$style."'>".$v['phone_1']."<br>".$v['phone_2']."</td>";
											}elseif($columns[$key]["column"] == "actions"){
												echo  "<td style='".$style."'><button style='margin-right:10px' data-page='".$table_name."' class='btn btn-red remove_ligne' value='".$v["id"]."'><i class='fas fa-trash-alt'></i></button><button data-page='".$table_name."' class='btn btn-orange edit_ligne' value='".$v["id"]."'><i class='fas fa-edit'></i></button></td>";												
											}else{
												echo  "<td>NaN</td>";
											}
											
										}

										
									}

								?>

							</tr>
							<?php
								$i++;
								}
							if ($i === 0){
								echo "<tr><td colspan='" . (count($columns) + 1) . "'>".$content."</td></tr>";
							}
							?>
							

						</tbody>
					</table>
					
				</div>
			</div>
		</div>
	</div>
		
	<div class="debug_client"></div>
