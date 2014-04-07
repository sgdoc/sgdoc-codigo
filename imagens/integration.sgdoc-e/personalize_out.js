/**
 * Este script é parte integrante do sistema SGDoc
 * usado na integracao do SGODoc-e com o SGDoc Físico
 *
 * @author j. augusto <augustowebd@gmail.com>
 * @date 2014-01-10
 * */
(function ($) {

    /* remove espacos da string que operar */
    String.prototype.trim = function () {
        return this.replace(/^\s+|\s+$/g, '');
    };

    /* traduz a estring para realiadade do SGDocPersonalize */
    String.prototype.translate = function ()
    {
       return this.toLowerCase()
                   .trim()
                   .split(' ')
                   .join('');
    }

    /**
     * objeto para personalizacao do SOGDoc-E considerando
     * sua integracao com o SGDoc Físico
     * */
    var SGDocPersonalize = function () {
        this.toolbarinit = false;

        this.storage = {};

        this.storage.icons = {};
    };

    /**
     * cria estrutura iframe
     * */
    SGDocPersonalize.prototype.createStructure = function (settings) {
        /* elementos base */
        this.storage.elements = {};
        this.storage.elements.doctainer  = $('<div>');
        this.storage.elements.docframe   = $('<iframe>').attr('name', 'docframe');
        this.storage.elements.doctoolbar = $('<div>');

        /* aplica configuracao no elementos */
        for (var key in settings.elements) {
            var elm = this.storage.elements[key];
            for (var cfg in settings.elements[key]) {
                for (var o in settings.elements[key][cfg]) {
                    elm[cfg](o, settings.elements[key][cfg][o]);
                }
            };
        }

         this.storage
             .elements
             .doctainer
             .append(
                this.storage.elements.docframe,
                this.storage.elements.doctoolbar
            );
    };

    /**
     * registra icone
     *
     * @param string key
     * @param string cls
     * */
    SGDocPersonalize.prototype.registerIcon = function (key, cls)
    {
        this.storage.icons[key.translate()] = cls;
    }

    /**
     * renderiza os menus
     * */
    SGDocPersonalize.prototype.renderMenu = function (data) {

        if (this.toolbarinit) {
            return;
        }

        /* cria os btn do menu */
        for (var key in data) {

            var $icon = $('<i>');
            var $anchor  = $('<a>');

            $anchor.addClass('btn');
            $anchor.attr('href', data[key].href);
            $anchor.attr('title', data[key].text);
            $anchor.attr('target', 'docframe');

            $anchor.append($icon);
            $icon.addClass( this.storage.icons[key] )

            this.storage.elements.doctoolbar.append($anchor);
        }

        this.toolbarinit = !this.toolbarinit;
    };

    /**
     * entrada principal do objeto de configuracaothis.toolbarinit
     * */
    SGDocPersonalize.prototype.main = function (settings) {

        /* monta estrutura */
        this.createStructure(settings);

        /* registra icones */
        for (var key in settings.icons) {
            this.registerIcon(key, settings.icons[key]);
        }

        var that = this;

        /* monitora o evento que sera disparado de dentro do iframe */
        $.pm.bind(settings.messenger, function(data) {
            that.renderMenu(data);
        });

        /* adiciona o frame ao corpo do documento corrente */
        $('body').append( this.storage.elements.doctainer );
    };


/**
 * este bloco pode ser executado de outro script
 *
 * executa a personalizacao do SGDOC
 * */
$(document).ready(function (){

    var sgdoce = new SGDocPersonalize();

    sgdoce.main({
        /* nome do evento */
        messenger: 'doPersonalize',

        /*
         * define as caracteristicas do objeto container.
         * A estrutura deve atender aos nomes dos metodos jQuery
         * */
        elements: {
            doctainer: {
                attr: {id: 'doctainer'},
                 css: {
                    border: '1px solid green',
                     width: '1200px',
                    height: '780px',
                     float: 'center',
                 marginLeft: '70px'
                }
            },

            docframe: {

                attr: {id: 'docframe', 'src': 'http://dev.sgdoce.sisicmbio.icmbio.gov.br'},
                 css: {border: '1px solid red', width: '100%', height: '99%'}
            },

            doctoolbar: {
                attr: {id: 'doctoolbar'},
                 css: {border: '1px solid green', width: '500px', height: '35px', float: 'right'}
            }
        },

        icons: {
            'Sequencial de Unidade' : 'icon-glass',
            'Tipo de Prioridade'    : 'icon-heart',
            'mensagem'              : 'icon-film',
            'minutaeletrônica'     : 'icon-ok',
            'Modelo Carimbo'        : 'icon-off',
            'Modelo de Minuta'      : 'icon-home',
            'Tipo de Documento'     : 'icon-download-alt',
            'Vinculo de Prazo'      : 'icon-play-circle',
            'Caixa de Minutas'      : 'icon-lock'
        }
    });
});

})(jQuery)