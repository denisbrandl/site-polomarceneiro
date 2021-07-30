var formData = new FormData();
var campos = '';
var dataTable = null;
$(document).ready(function() {
	
	$.getJSON(url_global+"/api/BuscaCamposExibir", function(dados){
		campos = dados;
	   dataTable =  $('#tableMateriais').DataTable( {
			"processing": true,
			"serverSide": true,
			"responsive": window.innerWidth < 1000 ? true : false,
			"ajax": {
				"url": url_global + "/api/ListaMateriais",
				"data": function(data) {
					var buscaPorUsuario = $('#buscaPorUsuario').val();
					
					data.handle_usuario = buscaPorUsuario;
				}
			},
			"columns": campos,
			"order": [[1, 'asc']],
			language: {
				url: url_global + '/painel/js/datatables-pt-BR.json'
			}
		} );		
	});	
	
	$('#buscaPorUsuario').change(function(){
		dataTable.draw();
	});	
	
	$('body').on('click', '.excluirMaterial', function() {		
		$("div.messages").empty();
		$("#tituloModal").empty();    	
		if (confirm('Deseja realmente excluir esse material?\n\nATENÇÃO: Processo irreversível!') == true) {
			$("body").addClass("loading");
			$.ajax({
				url: url_global+'/api/ExcluirMaterial/' + $(this).attr('handleMaterial'),
				type: 'delete',
				dataType: 'json',
				contentType: false,
				processData: false,
				data: formData,
				success: (function (dados) {
					if (dados.success == 'false') {
						$(".modal-title").append('Erro ao processar seus dados');
						$(".messages").append(dados.message);
					}			
					if (dados.success == 'true') {
						$('#tableMateriais').DataTable().ajax.reload();
						$(".messages").append(dados.message);
					}
					
					$("body").removeClass("loading");
					$('#modalMessages').modal('show');
					
					formData = new FormData();
				})
			});
		}
		return false;
	});
	
	$('body').on('click', '.editarMaterial', function() {
		window.location = 'cadastro_material.php?material=' + $(this).attr('handleMaterial');
	});	
} );