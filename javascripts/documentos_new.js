$().ready(function(){
    
    var posImageOrdem, cNextImageOrdem, nPrevImageOrdem, nNextImageOrdem = '';
    
    $('.pages').sortable({
        delay: 150,
        opacity: 0.8,
        revert: true,
        start: function(event, ui) {
            posImageOrdem = $(ui.item).attr('id');
        },
        stop: function(event, ui) {
            nPrevImageOrdem = parseInt($(ui.item).prev().attr('id'));
            nNextImageOrdem = parseInt($(ui.item).next().attr('id'));
            
            if (posImageOrdem != (nPrevImageOrdem + 1) ||
                posImageOrdem != (nNextImageOrdem - 1)
            ){

                $.post("../modelos/documentos/imagens.php", {
                    acao:    'atualiza-ordem',
                    digital: $('#digital').html(),
                    oldPos:  posImageOrdem,
                    newPosPrev: nPrevImageOrdem,
                    newPosNext: nNextImageOrdem
                }, function(data) {
                    if (data.success != 'true') {
                        alert(data.error);
                    }
                    location.reload();
                }, 'json');
            }

        }

    });
    
    $('input[name^="flag_status_"]').click(function(){
        var md5 = $(this).attr('md5');
        var status = $(this).val();
        var msg = ' desta imagem ';
        if(!md5){
            msg = ' de todas as imagens ';
        }
        if (!confirm('Deseja alterar o status ' + msg + ' para ' + $(this).next().html() + '?')){
            return false;
        }
        
        $.post("../modelos/documentos/imagens.php", {
            acao:    'alterar-status-imagem',
            digital: $('#digital').html(),
            md5:     md5,
            status:  status
        }, function(data) {
            if (data.success != 'true') {
                alert('Não foi possível atualizar o status desta página!');
            }
            location.reload();
        }, 'json');
    });
    
    $('div.page').mouseenter(function(){
        $(this).children('ul').fadeIn();
    }).delay(1000).mouseleave(function(){
        $(this).children('ul').hide();
    });
    
});