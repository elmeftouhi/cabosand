<?php session_start(); $core = $_SESSION['CORE']; 

$formToken=uniqid();
$return_page = "Client_Type";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Type
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">

	<div class="panel-content">

		<div class="menu_form">

			<h3 style="margin-left: 6px">Type du client</h3>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<input type="text" placeholder="Type" id="client_type" value="">
				</div>				
				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<div class="" style="position: relative; width: 125px">
						<div class="on_off off" id="client_type_status"></div>
						<span style="position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px">
							  Par Défaut
						</span>
					</div>
				</div>						
			</div>
		</div>		


	</div>


</div>

<div class="debug_client"></div>

