var formData = new FormData();
var itemMomento = '';
var numArquivos = 1;
$(document).ready(function() {
    var dtable= $('#tableMarcaLinhaCor').DataTable( {
        "processing": true,
        "serverSide": true,
		"responsive": window.innerWidth < 1000 ? true : false,
        "ajax": url_global + "/api/BuscaHierarquiaMarcaLinhaCor",
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

	$('body').on('click', '.editarMarca', function() {		
		$("div.messages").empty();
		$("#tituloModal").empty();
		$('.listaDeMarca').hide();
		$('.listaDeLinha').hide();
		$('.imagemCor').hide();

		$('#editar_descricao').val('');
			
		formData = new FormData();		
		if ($(this).val() > 0) {
			$.ajax({
				url: url_global+'/api/BuscaMarca/' + $(this).val(),
				type: 'get',
				dataType: 'json',
				contentType: false,
				processData: false,
				data: formData,
				success: (function (dados) {

					$('#editar_descricao').val(dados.descricao);
					formData.append('handle', dados.id_marca);
				})
			});	
		}
		itemMomento = 'marca';	

		$('#modalEditarCategoria').modal('show');		
		return false;
	});

	$('body').on('click', '.editarLinha', function() {		
		$("div.messages").empty();
		$("#tituloModal").empty();
		
		$('.listaDeMarca').show();
		$('.listaDeLinha').hide();
		$('.imagemCor').hide();
		$('#editar_descricao').val('');

		formData = new FormData();

		if ($(this).val() > 0) {
			$.ajax({
				url: url_global+'/api/BuscaLinhaPorId/' + $(this).val(),
				type: 'get',
				dataType: 'json',
				contentType: false,
				processData: false,
				data: formData,
				success: (function (dados) {

					$('#editar_descricao').val(dados.descricao);
					$('#id_marca').val(dados.id_marca);

					formData.append('handle', dados.id_linha);
				})
			});	
		}

		itemMomento = 'linha';
		$('#modalEditarCategoria').modal('show');		
		return false;
	});	

	$('body').on('click', '.editarCor', function() {		
		$("div.messages").empty();
		$("#tituloModal").empty();
		
		$('.listaDeMarca').show();
		$('.listaDeLinha').show();
		$('.imagemCor').show();

		$('#editar_descricao').val('');
		$('#imageResult').empty();
					
		formData = new FormData();
		if ($(this).val() > 0) {
			$.ajax({
				url: url_global+'/api/BuscaCorPorId/' + $(this).val(),
				type: 'get',
				dataType: 'json',
				contentType: false,
				processData: false,
				data: formData,
				success: (function (dados) {

					$('#editar_descricao').val(dados.descricao);
					$('#id_marca').val(dados.id_marca);

					consultaLinhas(dados.id_linha);

					strImagem = '<img class="img-fluid" src="../imagens/categorias/'+dados.imagem+'" alt="">';
					$('#imageResult').html(strImagem);

					formData.append('handle', dados.id_cor);
				})
			});
		} else {
			consultaLinhas('');
		}
		itemMomento = 'cor';
		
		$('#modalEditarCategoria').modal('show');		
		return false;
	});		

	$("#btnSalvarItem").click(function() {
		$("div.messages").empty();
		$("#editarMarcaLinhaCor").validate({
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

		if ($('#editarMarcaLinhaCor').valid() == false) {
			$(".messages").append('<p class="text-danger"><strong>Por favor revise os dados preenchidos.</strong></p>');
			return false;
		}

		formData.append('descricao', $('#editar_descricao').val());

		url_acao = '/api/Alterar';
		if (formData.get('handle') == null) {
			url_acao = '/api/Inserir';
		}		
		switch (itemMomento) {
			case 'marca':
				url_acao = url_acao + 'Marca';
			break;
			case 'linha':
				url_acao = url_acao + 'Linha';
				formData.append('id_marca', $('#id_marca').val());
			break;
			case 'cor':
				url_acao = url_acao + 'Cor';
				formData.append('id_linha', $('#id_linha').val());
			break;
			default:
				return false;
		}

		$.ajax({
			url: url_global+url_acao,
			type: 'post',
			dataType: 'json',
			contentType: false,
			processData: false,
			data: formData,
			success: (function (dados) {
				$('#tableMarcaLinhaCor').DataTable().ajax.reload();
				$(".messages").append('Informação modificada com sucesso!');
			})
		});

		consultaMarcas();
	});

	function consultaMarcas() {
		$('#id_marca').find('option').remove().end();
		$.getJSON(url_global+"/api/BuscaMarcas", function(dados){    
			$.each(dados,function(key, value) 
			{
				$('#id_marca').append('<option value=' + value.id_marca + '>' + value.descricao + '</option>');
			});
		});
	}
	consultaMarcas();

	function consultaLinhas(linha_selecionada = '') {	
		$('#id_linha').find('option').remove().end().append('<option value="">Carregando informações...</option>');
		$.getJSON(url_global+"/api/BuscaLinha/"+$('#id_marca').val(), function(dados){
			$('#id_linha').find('option').remove().end();
			$.each(dados,function(key, value) 
			{	
				if (value.id_linha == linha_selecionada) {
					$('#id_linha').append('<option selected="selected" value=' + value.id_linha + '>' + value.descricao + '</option>');	
				} else {
					$('#id_linha').append('<option value=' + value.id_linha + '>' + value.descricao + '</option>');
				}
			});
		});
	}

	$("#id_marca").change(function() {
		consultaLinhas();
	});

	$(document).ready(function() {
		$(window).keydown(function(event){
		  if(event.keyCode == 13) {
			event.preventDefault();
			return false;
		  }
		});
	});

	$('#upload').on('change', function () {
        readURL();
	});

	function readURL() {
		nome_arquivo = '';
		tipo_arquivo = '';
		const files = document.querySelector('[type=file]').files;
		msg_erro = '';
	
		var d = new Date();
	
		$('.alertaArquivoInvalido').hide();
		$('.alertaArquivoInvalido').empty();
		
		for (let i = 0; i < files.length; i++) {
			
			let file = files[i];
			let n = d.getTime();
			let numberRandom = Math.floor(Math.random() * 9999);
			let handleTemp = ""+n+numberRandom+i;
			if (!file.type.startsWith('image/')){
				msg_erro = msg_erro + '<p>Formato da imagem '+file.name+' invalido</p>';
				continue;
			}
	
			var sizeInMB = (file.size / (1024*1024)).toFixed(2);		
			if (sizeInMB > 2){
				msg_erro = msg_erro + '<p>Tamanho da imagem '+file.name+' invalido</p>';
				continue;
			}		
			
			const reader = new FileReader();
			let imagem_valida = false;
	
			
			reader.onload = function (e) {
				var image = new Image();
				image.src = e.target.result;
	
				image.onload = function() {
					imagem_valida = true;					
					strImagem = '<img class="img-fluid" src="'+e.target.result+'" alt="ABC">';
					$('#imageResult').html(strImagem);
				}
			};		
			
			reader.readAsDataURL(file);
			formData.append('files['+numArquivos+']', file);
		}
	
		if (msg_erro != '') {		
			$('.alertaArquivoInvalido').append(msg_erro);
			$('.alertaArquivoInvalido').show();
	
			setTimeout(
				function()  {
					$('.alertaArquivoInvalido').hide();
					$('.alertaArquivoInvalido').empty();
				},
				3000
			);
		}
	}	

} );