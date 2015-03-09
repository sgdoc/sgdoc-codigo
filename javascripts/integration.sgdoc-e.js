/***/

var Integration = {};

$(document).ready(function() {

    Integration.container = $('#menus');

    Integration.iframe = $('<iframe>');

    Integration.btnOpen = $(
            '<a href="#">' +
            '<img class="botao48" src="imagens/integration.sgdoc-e/integrationOpen.png" title="Abrir SGDOC-E">' +
            '</a>'
            );

    Integration.btnClose = $('<img>');

    Integration.container.css('width', '105%');

    Integration.iframe.hide();
    Integration.iframe.css('width', '100%');
    Integration.iframe.css('height', $(document).height() - 100);
    Integration.iframe.css('position', 'absolute');
    Integration.iframe.css('top', '-3px');
    Integration.iframe.css('left', '-5px');
    Integration.iframe.css('right', '0px');
    Integration.iframe.css('bottom', '0px');
    Integration.iframe.css('z-index', '9999999');
    Integration.iframe.css('float', 'left');
    Integration.iframe.css('border-top', '5px #ccc solid');
    Integration.iframe.css('border-bottom', '5px black solid');
    Integration.iframe.css('background-color', 'white');

    Integration.btnClose.hide();
    Integration.btnClose.css('position', 'absolute');
    Integration.btnClose.css('top', '13px');
    Integration.btnClose.css('right', '20px');
    Integration.btnClose.css('z-index', '99999999');
    Integration.btnClose.css('cursor', 'pointer');
    Integration.btnClose.attr('title', 'Voltar para o SGDoc');
    Integration.btnClose.attr('src', 'imagens/integration.sgdoc-e/integrationClose.png');

    Integration.btnOpen.click(function(e) {
        e.preventDefault();
        if (!confirm('Você esta prestes a acessar o módulo de minutas eletrônicas do SGDOC-E\nDeseja continuar?!')) {
            return false;
        }
        Integration.iframe.attr('src', 'http://dsvm.sgdoce.sisicmbio.icmbio.gov.br');
        Integration.iframe.fadeIn();
        Integration.btnClose.fadeIn();

        $('#nuCpf', Integration.iframe).hide();

    });

    Integration.btnClose.click(function(e) {
        e.preventDefault();
        if (!confirm('Você tem certeza que deseja voltar para o SGDOC?!')) {
            return false;
        }
        Integration.iframe.attr('src', '');
        Integration.iframe.fadeOut();
        Integration.btnClose.fadeOut();
    });

    Integration.btnClose.hover(function() {
        Integration.btnClose.attr('src', 'imagens/integration.sgdoc-e/integrationCloseHover.png');
    }, function() {
        Integration.btnClose.attr('src', 'imagens/integration.sgdoc-e/integrationClose.png');
    });

    Integration.container.append(Integration.btnOpen);
    Integration.container.append(Integration.btnClose);
    Integration.container.append(Integration.iframe);

});