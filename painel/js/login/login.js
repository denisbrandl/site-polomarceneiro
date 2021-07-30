$("#btnEnviarFormulario").click(function() {

	$("div.messages").empty();
	var dados = [];
	
	$("#frmFormularioPrincipal").validate({
		errorClass: 'erro-formulario',
		rules: {
			email: {
				required: true
			},
			senha: {
				required: true
			}
		},
		messages: {
			email: {
				required: "Informe o seu email"
			},
			senha: {
				required: "Informe a sua senha"
			}			
		}			
	});
	
	if ($('#frmFormularioPrincipal').valid() == false) {
		$(".messages").append('<p class="text-danger"><strong>Por favor revise os dados preenchidos.</strong></p>');
		$('#modalMessages').modal('show');
		return false;
	}	
	
	$("#frmFormularioPrincipal input[type='password'], #frmFormularioPrincipal input[type='email']").map(function(idx, elem) {
		if ($(elem).val() != '') {			
			dados[$(elem).attr('name')] = $(elem).val();
		}
	});
	
	$("#btnEnviarFormulario").prop('disabled',true);
	$("#btnEnviarFormulario").text('Processando informações');
	
    $.ajax({
        url: url_global+'/api/LoginUsuario',
        type: 'post',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(Object.assign({}, dados)),
		success: (function (dados) {
			if (dados.success == 'false') {
				$(".modal-title").append('Erro ao processar seus dados');
				$(".messages").append(dados.message);
				$("#btnEnviarFormulario").prop('disabled',false);
				$("#btnEnviarFormulario").text('Acessar minha conta');
			}
			if (dados.success == 'true') {
				$(".messages").append('<i class="fa-check-circle "></i>'+dados.message);
				setTimeout(function(){ window.location = 'index.php' }, 3000);
			}
			$('#modalMessages').modal('show');
			
		})
    });
});

$(".logout").click(function() {
	if (confirm('Deseja realmente sair da sua conta?')) {
		$.ajax({
			url: url_global+'/api/LogoutUsuario',
			type: 'get',
			dataType: 'json',
			success: (function (dados) {
				setTimeout(function(){ window.location = 'login.php' }, 3000);
			})
		});
	}
});

$('.recuperarSenha').click(function() {
	$('#modalRecuperarSenha').modal('show');
});

$("#btnEnviarFormularioRecuperarSenha").click(function() {

	$("div.messagesRecuperarSenha").empty();
	var dados = [];
	
	$("#frmFormularioRecuperarSenha").validate({
		errorClass: 'erro-formulario',
		rules: {
			email_recuperar: {
				required: true
			}
		},
		messages: {
			email_recuperar: {
				required: "Informe o seu email"
			}	
		}			
	});
	
	if ($('#frmFormularioRecuperarSenha').valid() == false) {
		$(".messagesRecuperarSenha").append('<p class="text-danger"><strong>Por favor revise os dados preenchidos.</strong></p>');	
		return false;
	}	
	
	$("#frmFormularioRecuperarSenha input[type='email']").map(function(idx, elem) {
		if ($(elem).val() != '') {			
			dados[$(elem).attr('name')] = $(elem).val();
		}
	});
	
	$("#btnEnviarFormularioRecuperarSenha").prop('disabled',true);
	$("#btnEnviarFormularioRecuperarSenha").text('Processando informações');
	
    $.ajax({
        url: url_global+'/api/RecuperarSenha',
        type: 'post',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(Object.assign({}, dados)),
		success: (function (dados) {
			if (dados.success == 'false') {
				$(".messagesRecuperarSenha").append('<p class="alert alert-warning">'+dados.message+'</p>');
				$("#btnEnviarFormularioRecuperarSenha").prop('disabled',false);
				$("#btnEnviarFormularioRecuperarSenha").text('Acessar minha conta');
			}
			if (dados.success == 'true') {
				$(".messagesRecuperarSenha").append('<p class="alert alert-success">'+dados.message+'</p>');
			}
		})
    });
});

$(document).on('keypress',function(e) {
    if(e.which == 13) {
        $('#btnEnviarFormulario').trigger("click");
    }
});