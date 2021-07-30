var formData = new FormData();
var itemMomento = '';
var numArquivos = 1;
$(document).ready(function() {
    var dtable= $('#tableCategoriaSubcategoria').DataTable( {
        "processing": true,
        "serverSide": true,
		"responsive": window.innerWidth < 1000 ? true : false,
        "ajax": url_global + "/api/BuscaHierarquiaCategoriaSubcategoria",
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

	$('body').on('click', '.editarCategoria', function() {		
		$("div.messages").empty();
		$("#tituloModal").empty();
		$('.listaDeCategoria').hide();

		$('#editar_descricao').val('');
			
		formData = new FormData();		
		if ($(this).val() > 0) {
			$.ajax({
				url: url_global+'/api/BuscaCategoria/' + $(this).val(),
				type: 'get',
				dataType: 'json',
				contentType: false,
				processData: false,
				data: formData,
				success: (function (dados) {
					$('#editar_descricao').val(dados[0].descricao);
					formData.append('handle', dados[0].id_categoria);
				})
			});	
		}
		itemMomento = 'categoria';	

		$('#modalEditarCategoria').modal('show');		
		return false;
	});

	$('body').on('click', '.editarSubCategoria', function() {		
		$("div.messages").empty();
		$("#tituloModal").empty();
		
		$('.listaDeCategoria').show();
		
		$('#editar_descricao').val('');

		formData = new FormData();
		if ($(this).val() > 0) {
			$.ajax({
				url: url_global+'/api/BuscaCategoria/' + $(this).val(),
				type: 'get',
				dataType: 'json',
				contentType: false,
				processData: false,
				data: formData,
				success: (function (dados) {
					console.log('id_categoria: ' + dados[0].id_categoria);
					$('#editar_descricao').val(dados[0].descricao);
					$('#id_categoria_pai').val(dados[0].id_categoria_pai);

					formData.append('handle', dados[0].id_categoria);
				})
			});	
		}

		itemMomento = 'subcategoria';
		$('#modalEditarCategoria').modal('show');		
		return false;
	});
	
	$("#btnSalvarItem").click(function() {
		$("div.messages").empty();
		$("#editarCategoriaSubcategoria").validate({
			errorClass: 'erro-formulario',
			rules: {
				editar_descricao: {
					required: editar_descricao
				}
			},
			messages: {
				editar_descricao: {
					required: "Informe a descrição do item"
				}
			}			
		});

		if ($('#editarCategoriaSubcategoria').valid() == false) {
			$(".messages").append('<p class="text-danger"><strong>Por favor revise os dados preenchidos.</strong></p>');
			return false;
		}

		formData.append('descricao', $('#editar_descricao').val());

		url_acao = '/api/AlterarCategoria';
		if (formData.get('handle') == null) {
			url_acao = '/api/InserirCategoria';
		}		
		
		if (itemMomento == 'subcategoria') {
			formData.append('id_categoria_pai', $('#id_categoria_pai').val());
		}

		$.ajax({
			url: url_global+url_acao,
			type: 'post',
			dataType: 'json',
			contentType: false,
			processData: false,
			data: formData,
			success: (function (dados) {
				$('#tableCategoriaSubcategoria').DataTable().ajax.reload();
				$(".messages").append('Informação modificada com sucesso!');
				formData = new FormData();
				formData.append('handle', dados);
				consultaCategorias();
			})
		});
	});

	function consultaCategorias(categoria_selecionada = '') {	
		$('#id_categoria_pai').find('option').remove().end().append('<option value="">Carregando informações...</option>');
		$.getJSON(url_global+"/api/BuscaCategorias", function(dados){
			$('#id_categoria_pai').find('option').remove().end();
			$.each(dados,function(key, value) 
			{	
				if (value.id_categoria == categoria_selecionada) {
					$('#id_categoria_pai').append('<option selected="selected" value=' + value.id_categoria + '>' + value.descricao + '</option>');	
				} else {
					$('#id_categoria_pai').append('<option value=' + value.id_categoria + '>' + value.descricao + '</option>');
				}
			});
		});
	}
	consultaCategorias();

	$(document).ready(function() {
		$(window).keydown(function(event){
		  if(event.keyCode == 13) {
			event.preventDefault();
			return false;
		  }
		});
	});

} );