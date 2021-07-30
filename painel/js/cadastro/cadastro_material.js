var fileTypes = ['jpg', 'jpeg', 'png'];
var nome_arquivo = '';
var tipo_arquivo = '';
var conteudo_arquivo =  '';
var formData = new FormData();
var numArquivos = 0;

function consultaSubCategorias(subCategoriaSelecionar = '') {
    $('#id_subcategoria').find('option').remove().end().append('<option value="">Carregando informações...</option>');
    $.getJSON(url_global+"/api/BuscaSubCategorias/"+$('#id_categoria').val(), function(dados){
		$('#id_subcategoria').find('option').remove().end();
        $.each(dados,function(key, value) 
        {	
			selected = '';
			if (value.id_categoria == subCategoriaSelecionar) {
				selected = 'selected';
			}			
            $('#id_subcategoria').append('<option ' + selected + ' value=' + value.id_categoria + '>' + value.descricao + '</option>');
        });
    });   
}

function consultaLinhas(linhaSelecionar = '', corSelecionar = '') {
	
	$('#id_linha').find('option').remove().end().append('<option value="">Carregando informações...</option>');
    $.getJSON(url_global+"/api/BuscaLinha/"+$('#id_marca').val(), function(dados){
		$('#id_linha').find('option').remove().end();
        $.each(dados,function(key, value) 
        {	
			selected = '';
			if (value.id_linha == linhaSelecionar) {
				selected = 'selected';
			}					
            $('#id_linha').append('<option ' + selected + ' value=' + value.id_linha + '>' + value.descricao + '</option>');
        });    
        consultaCores(corSelecionar);
    });
    
}

function consultaCores(corSelecionar = '') {
	$('#id_cor').find('option').remove().end().append('<option value="">Carregando informações...</option>');
    $.getJSON(url_global+"/api/BuscaCor/"+$('#id_linha').val(), function(dados){
		$('#id_cor').find('option').remove().end();
		
		$.each(dados,function(key, value) 
        {
			selected = '';
			if (value.id_cor == corSelecionar) {
				selected = 'selected';
			}
            $('#id_cor').append('<option ' + selected + ' imagemCategoria="'+value.imagem+'" value=' + value.id_cor + '>' + value.descricao + '</option>');
        });


		exibirImagemCategoria();
	});
	
	return true;
}

$('#id_cor').change(function() {
	if ($('#id_cor').val() == 0) {
		return false;
	}
	exibirImagemCategoria();
});

function exibirImagemCategoria() {
	$('.preVisualizacaoImagemCategoria').empty();
	$('.preVisualizacaoImagemCategoria').append('<img style="width:200px; height:80px;" src="../imagens/categorias/'+$("#id_cor option:selected").attr('imagemCategoria')+'"><p><a href="../imagens/categorias/'+$("#id_cor option:selected").attr('imagemCategoria')+'" target="_blank">Visualizar imagem</a></p>');
	$('.exibirImagemCategoria').show();	
}

$("#id_linha").change(function() {
	consultaCores();
});

$("#id_marca").change(function() {
	consultaLinhas();
});

$("#id_categoria").change(function() {
	consultaSubCategorias();
});


$("#btnEnviarFormulario").click(function() {

	$("div.messages").empty();
    $("#tituloModal").empty();    

	$("#frmFormularioPrincipal").validate({
		errorClass: 'erro-formulario',
		rules: {
			titulo: {
				required: true,
				minlength: 5
			}
		},
		messages: {
			titulo: {
				required: "Por favor informe um título",
				minlength: "O título necessita ter pelo menos 5 caracteres"
			}
		}
	});

    if ($("#frmFormularioPrincipal").valid() == false) {
        $(".messages").append('<p class="text-danger"><strong>Por favor revise os dados preenchidos.</strong></p>');
        $('#modalMessages').modal('show');
        return false;
    }
	
	$("#btnEnviarFormulario").text('Processando informações');	

    var dados = [];
    $("#frmFormularioPrincipal input[type='text'], #frmFormularioPrincipal input[type='number'], #frmFormularioPrincipal select, #frmFormularioPrincipal textarea").map(function(idx, elem) {
        if ($(elem).val() != '') {
            dados[$(elem).attr('name')] = $(elem).val();
			
			formData.append($(elem).attr('name'), $(elem).val());
        }       
    });

	if (material > 0) {
		formData.append('handle_material', material);
	}

    $.ajax({
        url: url_global+'/api/CadastroMaterial',
        type: 'post',
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
                $(".messages").append(dados.message);
            }
            $('#modalMessages').modal('show');
			
			$("#btnEnviarFormulario").prop('disabled',false);
			$("#btnEnviarFormulario").text('Salvar');
			
			formData = new FormData();
        })
    });     
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
				strImagem = '<div class="col-lg-2 col-md-3 col-6 '+handleTemp+'"><img class="img-fluid" src="'+e.target.result+'" alt="ABC"><p class="excluirImagem" imagemId="'+handleTemp+'">X</p></div>';
				$('#imageResult').append(strImagem);
			}
		};		
		
		reader.readAsDataURL(file);
		formData.append('files['+numArquivos+']', file);
		numArquivos++;
		if (numArquivos == 2) {
			validaQuantidadeImagens();
			break;
		}
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

function buscaMaterial(material) {	
    $.getJSON(url_global+"/api/BuscaMaterial/"+material, function(dados){
		if (dados.erro != '') {
			alert(dados.erro);
			window.location = 'listar_material.php';
			return false;
		}
		$('#titulo').val(dados[0].titulo);
		$('#descricao').val(dados[0].descricao);
		$('#id_categoria').val(dados[0].id_categoria);
		consultaSubCategorias(dados[0].id_subcategoria);
		$('#quantidade').val(dados[0].quantidade);
		$('#quantidade_venda').val(dados[0].quantidade_venda);
		$('#tipo_venda').val(dados[0].tipo_venda);
		$('#unidade_medida').val(dados[0].unidade_medida);
		$('#largura').val(dados[0].largura);
		$('#altura').val(dados[0].altura);
		$('#id_espessura').val(dados[0].id_espessura);
		$('#profundidade').val(dados[0].profundidade);
		$('#situacao_anuncio').val(dados[0].situacao_anuncio);
		$('#id_marca').val(dados[0].id_marca);
		consultaLinhas(dados[0].id_linha, dados[0].id_cor);
		// consultaCores(dados[0].id_cor);

		if (dados[0].quantidade_venda >= 1) {
			$('.panelDisponivelVenda').show();
		} else {
			$('.panelDisponivelVenda').hide();
		}

        $.each(dados.imagem,function(key, value) 
        {
			strImagem = '<div class="col-lg-2 col-md-3 col-6 '+value.handle+'"><img class="img-fluid" src="../imagens/'+value.nome_arquivo+'" alt="ABC"><p><a class="excluirImagem" imagemId="'+value.handle+'">X</a></p></div>';
			$('#imageResult').append(strImagem);
			numArquivos++;
			if (numArquivos == 2) {
				validaQuantidadeImagens();
			}			
		});
		
		$("body").removeClass("loading");

    });
    
}

$(function () {
    $('#upload').on('change', function () {
        readURL();
	});


	
	$.getJSON(url_global+"/api/BuscaCategorias", function(dados){    
		$.each(dados,function(key, value) 
		{
			$('#id_categoria').append('<option value=' + value.id_categoria + '>' + value.descricao + '</option>');
		});
		consultaSubCategorias();
	});
	
	$.getJSON(url_global+"/api/BuscaMarcas", function(dados){    
		$.each(dados,function(key, value) 
		{
			$('#id_marca').append('<option value=' + value.id_marca + '>' + value.descricao + '</option>');
		});
		consultaLinhas();
	});
	
	$.getJSON(url_global+"/api/BuscaEspessuras", function(dados){    
		$.each(dados,function(key, value) 
		{
			$('#id_espessura').append('<option value=' + value.id_espessura + '>' + value.valor + '</option>');
		});
	});	

	$("#largura, #altura").change(function() {
		var largura = $("#largura").val();
		var altura = $("#altura").val();
		var profundidade = 0;

		if (largura > 0 && altura > 0) {
			profundidade = largura * altura;
		}

		$("#profundidade").val(profundidade);
	});

	$("#quantidade_venda").change(function() {
		if ($('#quantidade_venda').val() >= 1) {
			$('.panelDisponivelVenda').show();
		} else {
			$('#situacao_anuncio').val(0);
			$('.panelDisponivelVenda').hide();
		}
	});

	setTimeout(
		function(){
			if (material > 0) {
				buscaMaterial(material);
			}			
		}, 3000);


		$('body').on('click', '.excluirImagem', function() {
			if (confirm('Tem certeza que deseja excluir essa imagem?') == true) {
				var nomeArquivo = $(this).attr('imagemId');
				formData.append('arquivoExcluir[]', nomeArquivo);
				$('.'+nomeArquivo).remove();				
				numArquivos--;
				validaQuantidadeImagens();
			}
		});				
});

function validaQuantidadeImagens() {
	if (numArquivos >= 2) {
		$('.uploadImagens').hide();
		$('.uploadImagensDesabilitado').show();
	} else {
		$('.uploadImagens').show();
		$('.uploadImagensDesabilitado').hide();		
	}
}