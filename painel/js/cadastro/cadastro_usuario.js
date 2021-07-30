var fileTypes = ['jpg', 'jpeg', 'png'];  //acceptable file types
var nome_arquivo = '';
var tipo_arquivo = '';
var conteudo_arquivo =  '';
$("#btnEnviarFormulario").click(function() {

	$("div.messages").empty();
	$("#tituloModal").empty();

	var senha_obrigatoria = true;
	if (usuario > 0) {
		senha_obrigatoria = false;
	}
	
	$("#frmFormularioPrincipal").validate({
		errorClass: 'erro-formulario',
		rules: {
			cnpj: {
				required: true
			},
			inscricao_estadual: {
				required: true
			},
			razao_social: {
				required: true
			},
			nome_fantasia: {
				required: true
			},
			nome_completo: {
				required: true
			},
			email: {
				required: true
			},
			senha: {
				required: senha_obrigatoria
			},
			senha2: {
				required: senha_obrigatoria
			},
			telefone: {
				required: true
			},
			cep: {
				required: true
			},
			endereco: {
				required: true
			},
			bairro: {
				required: true
			},
			cidade: {
				required: true
			},
			uf: {
				required: true
			}
		},
		messages: {
			cnpj: {
				required: "Informe o CNPJ"
			},
			inscricao_estadual: {
				required: "Informe a inscrição estadual"
			},
			razao_social: {
				required: "Preencha a razão social"
			},
			nome_fantasia: {
				required: "Preencha o nome fantasia"
			},
			nome_completo: {
				required: "Preencha seu nome completo"
			},
			email: {
				required: "Preencha seu endereço de e-mail"
			},
			senha: {
				required: "Insira uma senha"
			},
			senha2: {
				required: "Insira a confirmação da senha"
			},
			telefone: {
				required: "Informe um número de telefone"
			},
			cep: {
				required: "Insira o seu CPF"
			},
			endereco: {
				required: "Insira um endereço válido"
			},
			bairro: {
				required: "Insira o bairro"
			},
			cidade: {
				required: "Insira a cidade"
			},
			uf: {
				required: "Insira o estado (UF)"
			}
		}
	});	
	
	if ($('#frmFormularioPrincipal').valid() == false) {
		$(".messages").append('<p class="text-danger"><strong>Por favor revise os dados preenchidos.</strong></p>');
		$('#modalMessages').modal('show');
		return false;
	}	
	

	$('.cnpj-invalido').hide();
	if (validarCNPJ($('#cnpj').val()) == false) {
		$('.cnpj-invalido').show();
		$(".messages").append('<p class="text-danger"><strong>Por valide o CNPJ informado.</strong></p>');
		$('#modalMessages').modal('show');
		return false;
	}

	$('.senhas-diferentes').hide();
	if ($('#senha').val() != $('#senha2').val()) {
		$('.senhas-diferentes').show();
		$(".messages").append('<p class="text-danger"><strong>As duas senhas informadas precisam ser iguais.</strong></p>');
		$('#modalMessages').modal('show');
		return false;
	}
	
	$("#btnEnviarFormulario").prop('disabled',true);
	$("#btnEnviarFormulario").text('Processando informações')	

	var dados = [];
	
	var str = '';
	$("#frmFormularioPrincipal input[type='text'], #frmFormularioPrincipal input[type='password'], #frmFormularioPrincipal input[type='email'], #frmFormularioPrincipal select").map(function(idx, elem) {
		if ($(elem).val() != '') {			
			dados[$(elem).attr('name')] = $(elem).val();
		}
	});
	
	if (nome_arquivo !== '' && conteudo_arquivo != '') {
		dados['nome_arquivo'] = nome_arquivo;
		dados['conteudo_arquivo'] = conteudo_arquivo;
	}

	if (usuario > 0) {
		dados['handle_usuario'] = usuario;
	}	

    $.ajax({
        url: url_global + '/api/CadastroUsuario',
        type: 'post',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(Object.assign({}, dados)),
		success: (function (dados) {
			if (dados.success == 'false') {
				$(".modal-title").append('Erro ao processar seus dados');
				$(".messages").append(dados.message);
			}			
			if (dados.success == 'true') {
				$(".messages").append(dados.message + 'Você será direcionado para fazer o login de acesso');

				setTimeout(function(){ window.location = '../painel/login.php' }, 3000);
			}
			$('#modalMessages').modal('show');

			$("#btnEnviarFormulario").prop('disabled',false);
			$("#btnEnviarFormulario").text('Salvar');
			
		})
    });
});

function buscaUsuario(usuario) {	
	cidade_usuario = 0;
    $.getJSON(url_global+"/api/BuscaUsuario/"+usuario, function(dados){
		$.each(dados, function(key, value) {
			$('#'+key).val(value);
			if (key == 'cidade') {
				cidade_usuario = value;
			}
		});

		buscaMunicipioPorEstado(cidade_usuario);		
		
        $.each(dados.imagem,function(key, value) 
        {

			// strImagem = '<div class="col-lg-2 col-md-3 col-6"><img class="img-fluid" src="../imagens/'+value.nome_arquivo+'" alt="ABC"><p>X</p></div>';
			// $('#imageResult').append(strImagem);
			// numArquivos++;
		});

    });
    
}

var arrEstadosSiglaCodigo = Array();
$(function(){

	$('#uf').find('option').remove().end().append('<option value="">Carregando estados...</option>');
	$.getJSON(url_global+"/api/BuscaEstados/", function(dados){
		$('#uf').find('option').remove().end();
		$.each(dados,function(key, value) 
		{	
			selected = '';
			// if (value.id_categoria == subCategoriaSelecionar) {
			// 	selected = 'selected';
			// }			
			$('#uf').append('<option ' + selected + ' value=' + value.codigo_uf + '>' + value.nome + '</option>');
			
			arrEstadosSiglaCodigo[value.uf] = value.codigo_uf;
		});
	
		if (usuario > 0) {
			buscaUsuario(usuario);
		}

	});
	
	
	$('input[name="cep"]').blur(function(){
		var cep = $.trim($('input[name="cep"]').val().replace('-', ''));

		 $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados){
			if(!("erro" in dados)){
				console.log(dados);
				$('input[name="endereco"]').val(dados.logradouro);
				$('input[name="bairro"]').val(dados.bairro);
				// $('input[name="cidade"]').val(unescape(dados.localidade));
				$('select[name="uf"]').find('option[value="'+arrEstadosSiglaCodigo[dados.uf]+'"]').attr('selected', true);
				buscaMunicipioPorEstado(dados.ibge);
			}
		});
	});	
});

$('#uf').change(function() {
	buscaMunicipioPorEstado();	
});

function buscaMunicipioPorEstado(id_municipio = '') {
	$('#cidade').find('option').remove().end().append('<option value="">Carregando municipios...</option>');
	$.getJSON(url_global+"/api/BuscaMunicipio/"+$('#uf').val(), function(dados){
		$('#cidade').find('option').remove().end();
        $.each(dados,function(key, value) 
        {	
			selected = '';
			if (value.codigo_ibge == id_municipio) {
				selected = 'selected';
			}			
            $('#cidade').append('<option ' + selected + ' value=' + value.codigo_ibge + '>' + value.nome + '</option>');
        });

    });
}

var behavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
options = {
    onKeyPress: function (val, e, field, options) {
        field.mask(behavior.apply({}, arguments), options);
    }
};

$('#telefone').mask(behavior, options);
$("#cep").mask("00000-000");
$("#cnpj").mask("00.000.000/0000-00")


function readURL(input) {
	nome_arquivo = '';
	tipo_arquivo = '';
	conteudo_arquivo =  '';	
    if (input.files && input.files[0]) {
		var extension = input.files[0].name.split('.').pop().toLowerCase(),
		isSuccess = fileTypes.indexOf(extension) > -1;
		
		var reader = new FileReader();
		if (isSuccess) {

			reader.onload = function (e) {
				$('#imageResult')
					.attr('src', e.target.result);
					
				conteudo_arquivo = e.target.result;
			};
			reader.readAsDataURL(input.files[0]);
			nome_arquivo = input.files[0]['name'];
			tipo_arquivo = input.files[0]['image/png'];
			
			
		} else {
			$('#imageResult').attr('src', '');
			$('#upload').attr('value', '');
		}
    }
}

$(function () {
    $('#upload').on('change', function () {
        readURL(input);
    });
});

/*  ==========================================
    SHOW UPLOADED IMAGE NAME
* ========================================== */
var input = document.getElementById( 'upload' );
var infoArea = document.getElementById( 'upload-label' );

input.addEventListener( 'change', showFileName );
function showFileName( event ) {
  var input = event.srcElement;
  var fileName = input.files[0].name;
  infoArea.textContent = 'Nome do arquivo: ' + fileName;
}