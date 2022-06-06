<?php
session_start();
$core = $_SESSION["CORE"];
require_once($core."Calendar.php");
require_once($core."Complexe.php");
require_once($core."Client.php");
$complexes = $complexe->find("",array("conditions"=>array("status="=>1),"order"=>"name"),"v_complexe");

require_once($core."Contrat.php");

$months = [
	1	=>	'Janvier',
	2	=>	'Février',
	3	=>	'Mars',
	4	=>	'Avril',
	5	=>	'Mai',
	6	=>	'Juin',
	7	=>	'Juillet',
	8	=>	'Août',
	9	=>	'Septembre',
	10	=>	'Octobre',
	11	=>	'Novembre',
	12	=>	'Décembre'
];

$first_year = 2019;
$this_year = date('Y');
$years = [];
for($year=$first_year; $year<=$this_year; $year++){
	array_push($years, $year);
}


?>
<div id="page" class="dashbord">
	<div class="head">
		<div class="title">
			<div class="name"><i class="fas fa-chart-line"></i> Dashbord</div>
		</div>
	</div>

	
	
	<div class="body pb-8 border h-full">
		<!-- Draw here the calendar by Month and Client-->
		<?= $calendar->Table_Month([]) ?>

		<div class="shadow rounded border mx-2 mt-8 mb-24">
			<div class="py-2 bg-white px-2 flex items-center gap-4 justify-between">
				<div class="flex items-center gap-4 filters">
					<select class="rounded-lg px-2" id="complexe">
						<option value="-1">-- Complexe </option>
						<?php foreach($complexe->find('', ['order'=>'name asc'], 'complexe') as $c){ ?>
							<option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
						<?php } ?>
					</select>
					<select class="rounded-lg px-2" id="appartement">
						<option value="-1">-- Appartement </option>
					</select>
					<select class="rounded-lg px-2" id="client">
						<option value="-1">-- Client </option>
						<?php foreach($client->find('', ['conditions'=>['id_status='=>11], 'order'=>'first_name asc'], 'client') as $c){ ?>
							<option value="<?= $c['id'] ?>"><?= $c['societe_name']==''? $c['first_name'].' '.$c['last_name']: $c['societe_name']  ?></option>
						<?php } ?>
					</select>


					<div class="relative border rounded-lg text-gray-800 bg-white">
						<input placeholder="-- Appartement" type="text" class="input-list w-full py-1 pl-3" style="padding-right:24px !important">
						<div class="list-show absolute top-0 right-0 m-2 cursor-pointer text-gray-400 hover:text-gray-800">
							<i class="fa fa-chevron-down"></i>
						</div>
						<div class="z-50 list-container bg-white absolute top-0 left-0 w-48 overflow-y-auto max-h-64 mt-8 border rounded shadow  py-2">
						<?php foreach($complexe->find('', ['order'=>'name asc'], 'complexe') as $c){ ?>
							<div data-id="<?= $c['id'] ?>" class="list-item cursor-pointer py-1 px-3 hover:bg-gray-100 truncate ..."><?= $c['name'] ?></div>
						<?php } ?>
							
						</div>
					</div>



					<button class='run_search rounded-lg'>
						<i class="fa fa-search"></i>
					</button>
				</div>
				<div class="flex items-center gap-4">
					<select class="rounded-lg px-2" id="year">
						<?php foreach($years as $year){ ?>
							<option <?= $year == date('Y')? 'selected': '' ?> value="<?= $year ?>"><?= $year ?></option>
						<?php } ?>
					</select>
					<select class="rounded-lg px-2" id="month">
						<?php foreach($months as $key=>$month){ ?>
							<option <?= $key == date('m')? 'selected': '' ?> value="<?= $key ?>"><?= $month ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="calendar_by_societe">
				<table>
					<tr>
						<td class="bg-white" colspan="60"> 
							<div class="w-64 mx-auto my-8 text-xs text-red-800 font-light text-center flex flex-col items-center">
								<img class="h-24" src="https://us.v-cdn.net/6031209/uploads/W6CE78AAFKJ8/image.png">
								Aucune Période pour cette recherche
							</div>
						</td>
					</tr>
				</table>
			</div>

		</div>

	</div>
</div>

<script>
	$(document).ready(function(){

		$(document).mouseup(function(e) {
			var container = $(".list-container");
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				container.addClass('hidden')
			}
		});
		
		$(".list-show").on('click', function(){
			$('.list-container').toggleClass('hidden')
		})

		$(".input-list").on('click', function(){
			$('.list-container').toggleClass('hidden')
		})

		$(".input-list").on('input', function(){
			var request = $(this).val()
			request = request.toLowerCase()
			$(".list-item").each(function(){
				var txt = $(this).html().toLowerCase()
				if(txt.includes(request)){
					$(this).removeClass('hidden')
				}else{
					$(this).addClass('hidden')
				}
			})
		})

		$(".list-item").on('click', function(){
			$(".input-list").val($(this).html())
			$('.list-container').addClass('hidden')
		})

		/** Calendar Style By Month */
		$(document).on('click', '.calendar_body_refresh', function(){
			$('.calendar_body').html("Loading...")
			var month = $('.calendar_month').val();
			var year = $('.calendar_year').val();
			var data = {
				'controler'		:	'Calendar',
				'function'		:	'Table_Month_Body',
				'params'		:	{
					'month'			:	month,
					'year'			:	year
				}
			};
			$('.calendar_body').preloader();
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				$('.calendar_body').html(response.msg)
				$("#preloader").remove();
			}).fail(function(xhr) {
				console.log(xhr.responseText);
				$("#preloader").remove();
			});	


		})

		$(document).on('click', '.calendar_year_month_prev', function(){
			var current_year= $('.calendar_year').prop('selectedIndex');
			var current_month = $('.calendar_month').prop('selectedIndex');
			var total_months = $('.calendar_month option').length;
			var total_year = $('.calendar_year option').length;
			if(current_month == 0){
				if(current_year == 0){
					// Alert the first of the list
				}else{
					$('.calendar_month option').eq(total_months-1).prop('selected', true);
					$('.calendar_year option').eq(current_year-1).prop('selected', true);

				}
			}else{
				$('.calendar_month option').eq(current_month-1).prop('selected', true);
			}
			$('.calendar_body_refresh').trigger('click');
		})

		$(document).on('click', '.calendar_year_month_next', function(){
			var current_year= $('.calendar_year').prop('selectedIndex');
			var current_month = $('.calendar_month').prop('selectedIndex');
			var total_months = $('.calendar_month option').length;
			var total_year = $('.calendar_year option').length;

			if(current_month == total_months-1){
				if(current_year == total_year-1){
					// alert the end of the list
				}else{
					$('.calendar_month option').eq(0).prop('selected', true);
					$('.calendar_year option').eq(current_year+1).prop('selected', true);

				}
			}else{
				$('.calendar_month option').eq(current_month+1).prop('selected', true);
			}
			$('.calendar_body_refresh').trigger('click');
		})

		$(document).on('click', '.collapse', function(){
			var target_ = $(this).data('target');
			$('.'+target_).toggleClass('hidden');
			$(this).toggleClass('bg-red-400');
		})
		/** Calendar Style By Complexe/Appartement/Client */
		$('#month').on('change', function(){
			$('#year').trigger('change')
		});

		$('#year').on('change', function(){

			var year = $(this).val();
			var month = $('#month').val();
			var complexe = $('#complexe').val();

			var data = {
				'controler'		:	'Calendar',
				'function'		:	'Table_Complexe',
				'params'		:	{
					'month'			:	month,
					'year'			:	year,
					'complexe'		:	complexe
				}
			};
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){

				$(".filters").find('#complexe').remove()
				$(".filters").prepend(response.msg);
				$("#complexe").trigger('change')
				
			}).fail(function(xhr) {
				console.log(xhr.responseText);
			});	
		})

		$(document).on('change', '#complexe', function(){

			var year = $(this).val();
			var month = $('#month').val();
			var complexe = $('#complexe').val();
			var propriete = $('#appartement').val();

			var data = {
				'controler'		:	'Propriete',
				'function'		:	'ByComplexe',
				'params'		:	{
					'complexe'		:	complexe,
					'propriete'		:	propriete
				}
			};
			$("#appartement").addClass('bg-yellow-500')
			console.log(data)
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				$("#appartement")    
								.find('option')
    							.remove()
    							.end()
    							.append(response.msg)
				$("#appartement").removeClass('bg-yellow-500')
			}).fail(function(xhr) {
				console.log(xhr.responseText);
			});	
		})

		$('.run_search').on('click', function(){

			var year = $('#year').val();
			var month = $('#month').val();
			var complexe = $('#complexe').val();
			var client = $('#client').val();
			var appartement = $('#appartement').val();


			var data = {
				'controler'		:	'Calendar',
				'function'		:	'Table',
				'params'		:	{
					'month'			:	month,
					'year'			:	year,
					'complexe'		:	complexe,
					'client'		:	client,
					'appartement'	: 	appartement
				}
			};
			$(".calendar_by_societe").html("Loading.....");
			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
				$(".calendar_by_societe").html(response.msg);
				
			}).fail(function(xhr) {
				console.log(xhr.responseText);
			});			
		})
	});
</script>