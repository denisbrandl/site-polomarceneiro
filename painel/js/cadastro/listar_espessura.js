var formData = new FormData();
var itemMomento = '';
var numArquivos = 1;
$(document).ready(function() {
    var dtable= $('#tableEspessura').DataTable( {
        "processing": true,
        "serverSide": true,
		"responsive": window.innerWidth < 1000 ? true : false,
        "ajax": url_global + "/api/BuscaHierarquiaEspessura",
        "columns": [
            { "data": "item" },
            { "data": "editar" },
            { "data": "excluir" }
        ],
		"order": [[0, 'asc']],
		"pageLength": 50,
		language: {
            url: url_global + '/painel/js/datatables-pt-BR.json'
		},
		columnDefs: [
			{
				targets: 0,
				className: 'dt-body-left'
			}
		],
		"searching": true,
		"initComplete": function()
        {
         $(".dataTables_filter input")
          .unbind() // Unbind previous default bindings
          .bind("input", function(e) { // Bind our desired behavior
              // If the length is 3 or more characters, or the user pressed ENTER, search
              if(this.value.length > 3 || e.keyCode == 13) {
                  // Call the API search function
                  dtable.search(this.value).draw();
              }
              // Ensure we clear the search if they backspace far enough
              if(this.value == "") {
                  dtable.search("").draw();
              }
              return;
          });
          
        }		
    } );
	
		
	$('body').on('click', '.excluirEspessura', function() {		
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

	$('body').on('click', '.editarEspessura', function() {		
		$("div.messages").empty();
		$("#tituloModal").empty();

		$('#editar_valor').val('');
			
		formData = new FormData();	
		itemMomento = 'espessura';			
		if ($(this).val() > 0) {
			$.ajax({
				url: url_global+'/api/BuscaEspessura/' + $(this).val(),
				type: 'get',
				dataType: 'json',
				contentType: false,
				processData: false,
				data: formData,
				success: (function (dados) {
					$('#editar_valor').val(dados[0].valor);
					formData.append('handle', dados[0].id_espessura);
					
					$('#modalEditarEspessura').modal('show');
				})
			});	
		} else {
			$('#modalEditarEspessura').modal('show');
		}
		return false;
	});
	
	$("#btnSalvarItem").click(function() {
		$("div.messages").empty();
		$("#editarEspessura").validate({
			errorClass: 'erro-formulario',
			rules: {
				editar_valor: {
					required: editar_valor
				}
			},
			messages: {
				editar_valor: {
					required: "Informe um valor para a espessura"
				}
			}			
		});

		if ($('#editarEspessura').valid() == false) {
			$(".messages").append('<p class="text-danger"><strong>Por favor revise os dados preenchidos.</strong></p>');
			return false;
		}

		formData.append('valor', $('#editar_valor').val());

		url_acao = '/api/AlterarEspessura';
		if (formData.get('handle') == null) {
			url_acao = '/api/InserirEspessura';
		}		
		
		$.ajax({
			url: url_global+url_acao,
			type: 'post',
			dataType: 'json',
			contentType: false,
			processData: false,
			data: formData,
			success: (function (dados) {
				$('#tableEspessura').DataTable().ajax.reload();
				$(".messages").append('Informação modificada com sucesso!');
				formData = new FormData();
				formData.append('handle', dados);
			})
		});
	});

	$(document).ready(function() {
		$(window).keydown(function(event){
		  if(event.keyCode == 13) {
			event.preventDefault();
			return false;
		  }
		});
	});

} );