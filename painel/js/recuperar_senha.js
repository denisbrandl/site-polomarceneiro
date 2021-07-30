var formData = new FormData();
$('#recuperar').click(function() {

    $("#tituloModal").empty();
    $("div.messages").empty();

    $("#btnEnviarFormulario").prop('disabled',true);
    $("#btnEnviarFormulario").text('Processando...');    

    if ($('#senha').val() == '' || $('#senha2').val() == '') {
        $(".messages").append('<p class="text-danger"><strong>Por favor preencha todos os campos!</strong></p>');
        $('#modalMessages').modal('show');
        return false;        
    }

    if ($('#senha').val() != $('#senha2').val()) {
        $(".messages").append('<p class="text-danger"><strong>As senhas inseridas são diferentes</strong></p>');
        $('#modalMessages').modal('show');
        return false;        
    }
	
    var dados = [];
    $("#frmFormularioPrincipal input[type='password']").map(function(idx, elem) {
        if ($(elem).val() != '') {
            dados[$(elem).attr('name')] = $(elem).val();
			
			formData.append($(elem).attr('name'), $(elem).val());
        }       
    });	
	
    $.ajax({
        url: url_global+'/api/TrocarSenha/',
        type: 'post',
        dataType: 'json',
        contentType: false,
		processData: false,
        data: formData,
        success: (function (dados) {
            if (dados.success == 'false') {
                $(".modal-title").append('Erro enviar ao alterar a senha');
                $(".messages").append(dados.message);
            }			
            if (dados.success == 'true') {
                $(".modal-title").append('Sucesso');
                $(".messages").append(dados.message);
            }
            $('#modalMessages').modal('show');
			
			$("#btnEnviarFormulario").prop('disabled',false);
			$("#btnEnviarFormulario").text('Salvar');
			
			formData = new FormData();
        })
    }); 
});

$('.msgWhatsapp').click(function() {
    var numero_telefone = $(this).attr('data-whats');
    console.log(numero_telefone);
    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        window.open('https://api.whatsapp.com/send?phone='+numero_telefone.replace('tel:','')+'&text=Oi%2Cpeguei%20seu%20contato%20do%20site', '_blank');
    }else{
        window.open('https://web.whatsapp.com/send?phone='+numero_telefone.replace('tel:','')+'&text=Oi%2Cpeguei%20seu%20contato%20do%20site', '_blank');
    }  
});