;/*Funcoes - Documentos*/
function visualizar_imagens_documento(digital) {
    var paginas = jquery_quantidade_imagens_documento(digital);
    if (paginas > 0) {
        start_visualizador_documento(digital, '', paginas);
        $('#aux_digital').val(digital);
        $('.sortButton').fadeIn();
        $('.statusButton').hide();
    } else {
        alert('Este documento não possui imagens!');
    }

}

function start_visualizador_documento(digital, modo, paginas) {
    if (modo == "sort") {
        modo = modo + '_';
    }
    $('#frame_visualizador_imagem').attr('src', "visualizador/documentos.php?digital=" + digital + '&paginas=' + paginas + '&modo=' + modo);
    $('#visualizador_imagem').show();
}

function stop_visualizador_documento() {
    $('#visualizador_imagem').hide();
    $('#frame_visualizador_imagem').attr('src', '');
}

function openSort() {
    $('.sortButton').hide();
    start_visualizador_documento($('#aux_digital').val(), 'sort');
    $('.statusButton').show();
}

function openStatus() {
    $('.statusButton').hide();
    start_visualizador_documento($('#aux_digital').val(), '');
    $('.sortButton').show();
}

/*Funcoes - Processos*/
function visualizar_imagens_processo(numero_processo) {
    start_visualizador_processo(numero_processo, '');
    $('#aux_numero_processo').val(numero_processo);
    $('.sortButton').fadeIn();
    $('.statusButton').hide();
}

function start_visualizador_processo(numero_processo) {
    $('#frame_visualizador_imagem_processo').attr('src', "visualizador/processos.php?numero_processo=" + numero_processo);
    $('#visualizador_imagem_processo').show();
}

function stop_visualizador_processo() {
    $('#visualizador_imagem_processo').hide();
    $('#frame_visualizador_imagem_processo').attr('src', '');
}