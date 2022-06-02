<div class="w-full h-full px-2 relative">    
    <div class="hidden form absolute top-0 right-0 left-0 bottom-0 bg-gray-500 bg-opacity-30">
        <div class="border bg-gray-100 rounded-lg shadow p-4 mt-4 w-3/5 mx-auto">
            <div class="text-md font-bold">
                Ajouter / Modifier Statut d'appartement
            </div>
            <div class="my-4">
                <div class="text-xs mb-2">
                    Statut :
                </div>
                <input type="text" name="" value="fff" id="appartement_status" class="py-2 px-3 border border-gray-200 rounded">
                <input type="hidden" name="" id="id" value="0">
            </div>
            <div class="flex items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="is_default" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" required>
                </div>
                <label for="is_default" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Définir valeur par défaut
                </label>
            </div>
            <div class="flex items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="all_ligne" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" required>
                </div>
                <label for="all_ligne" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Toute la ligne
                </label>
            </div>
            <div class="pt-4 border-t border-gray-400 flex gap-4">
                <button class="btn btn-green enregistrer">
                    <i class="fas fa-floppy-disk"></i> 
                    Enregistrer
                </button>
                <button class="btn annuller">
                    Annuler
                </button>
            </div>
        </div>        
    </div>



    <div class="flex items-center justify-between">
        <div class="text-2xl">Statuts d'appartement</div>
        <div class="ajouter h-8 w-8 bg-green-500 rounded text-white text-lg justify-center flex items-center cursor-pointer hover:bg-green-600 focus:bg-red-700">
            <i class="fas fa-plus"></i>
        </div>
    </div>

    <div class="w-full my-4">
        <div class="border border-gray-300 bg-gray-200 h-10 px-2 flex items-center rounded-t-md">
            <div class="font-light w-32"> #ID </div>
            <div class="font-light flex-1"> Statut d'appartement </div>
            <div class="font-light w-64 text-center"> Draw </div>
            <div class="font-light w-64 text-center"> Par Défaut </div>
            <div class="font-light w-32"> </div>
        </div>
        <?php foreach($statuses as $status){ ?>
        <div class="border border-t-0 border-gray-300 hover:bg-gray-50 h-10 px-2 flex items-center">
            <div class="font-light w-32"> <?= $status["id"] ?> </div>
            <div class="font-light flex-1"> <?= $status["propriete_status"] ?> (<?= $status["nbr"] ?>) </div>
            <div class="font-light w-64 text-center"> <?= $status["all_ligne"]? "<span class='bg-green-200 rounded-lg py-1 px-4 border'><i class='fa-solid fa-list-check'></i>Toute la ligne</span>": "" ?> </div>
            <div class="font-light w-64 text-center"> <?= $status["is_default"]? "<span class='bg-green-200 rounded-lg py-1 px-4 border'><i class='fa-solid fa-list-check'></i> Par Défaut</span>": "" ?> </div>
            <div class="font-light w-32 flex justify-between"> 
                <div data-id="<?= $status["id"] ?>" data-appartement_status="<?= $status["propriete_status"] ?>" data-is_default="<?= $status["is_default"] ?>" class="modifier border rounded py-1 px-2 bg-gray-400 text-gray-900 rounded cursor-pointer hover:bg-gray-600 hover:text-white">
                    Modifier
                </div>
                <?php if($status["nbr"] == 0){ ?>
                <div data-id="<?= $status["id"] ?>"  class="supprimer rounded py-1 px-2 text-red-600 text-white rounded cursor-pointer hover:bg-red-600 hover:text-white flex items-center">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<script>
    $(document).ready(function(){

        /** Add New */
        $('.ajouter').on('click', function(){
            $("#id").val("")
            $("#appartement_status").val("")
            $('#is_default').prop('checked', false)
            $('.form').removeClass('hidden')
            $("#appartement_status").focus()
        })

        /** Edit */
        $('.modifier').on('click', function(){
            var appartement_status = $(this).data('appartement_status')
            var id = $(this).data('id')
            var is_default = $(this).data('is_default')
            $("#id").val(id)
            $("#appartement_status").val(appartement_status)
            if(is_default){
                $('#is_default').prop('checked', true)
            }else{
                $('#is_default').prop('checked', false)
            }
            $('.form').removeClass('hidden')
            $("#appartement_status").focus()
        })

        /** Save */
        $('.enregistrer').on('click', function(){
			var appartement_status = $('#appartement_status').val();
			var is_default = $('#is_default').prop("checked");
			var all_ligne = $('#all_ligne').prop("checked");

			var data = {
				'controler'		:	'Propriete_Status',
				'function'		:	'Store',
				'params'		:	{
					'propriete_status'			:	appartement_status,
					'is_default'			    :	is_default,
					'all_ligne'			        :	all_ligne
				}
			};

            if($('#id').val() != 0){
                data.params.id = $('#id').val()
            }

			$.ajax({
				type		: 	"POST",
				url			: 	"pages/default/ajax/ajax.php",
				data		:	data,
				dataType	: 	"json",
			}).done(function(response){
                $('.parametre_menu').find('.selected').trigger('click')
			}).fail(function(xhr) {
				console.log(xhr.responseText);
			});

        });

        /** Delete */
        $('.supprimer').on('click', function(){
            var id = $(this).data("id");
            if(confirm("Etes vous sur de vouloir supprimer? #ID = " + id)){
                
                var data = {
                    'controler'		:	'Propriete_Status',
                    'function'		:	'Remove',
                    'params'		:	{
                        'id'			:	id
                    }
                };

                $.ajax({
                    type		: 	"POST",
                    url			: 	"pages/default/ajax/ajax.php",
                    data		:	data,
                    dataType	: 	"json",
                }).done(function(response){
                    $('.parametre_menu').find('.selected').trigger('click')
                }).fail(function(xhr) {
                    console.log(xhr.responseText);
                });
            }
        })

        /** Abort operation */
        $('.annuller').on('click', function(){
            $('.form').addClass('hidden')
        })
    })
</script>