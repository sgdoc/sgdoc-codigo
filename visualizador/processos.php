<?php
$manager = AclFactory::checaPermissao(
    Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(998));

$allow = AclFactory::checaPermissao(
    Controlador::getInstance()->acl, Controlador::getInstance()->usuario, DaoRecurso::getRecursoById(999));

$informations = Imagens::factory()->recoverInformationDBByProcess($_REQUEST['numero_processo']);

//$digitais = array();

//var_dump($images); die;

foreach ($informations as $information) {
    $digitais[ $information['DIGITAL'] ] = $information['DIGITAL'];
}

$images = array();

foreach ($digitais as $digital) {
    $documentoImagemAgg = Documento\Imagem\DocumentoImagemFactory::factory( $digital );
    $imagensDoDocumento = $documentoImagemAgg->getImageList();

    if(!empty($imagensDoDocumento)){
        $images = array_merge( $images, $imagensDoDocumento );        
    }
}

if (empty($images)) {
    exit;
}

$urlPDF = sprintf("%s/modelos/imagens/getPDF.php?numero_processo=%s", __URLSERVERAPP__, $_REQUEST['numero_processo']);
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">

        <link rel="stylesheet" href="/plugins/galleriffic/css/basic.css" type="text/css" />
        <link rel="stylesheet" href="/plugins/galleriffic/css/galleriffic-5.css" type="text/css" />
        <link rel="stylesheet" href="/plugins/galleriffic/css/white.css" type="text/css" />

        <script type="text/javascript" src="/javascripts/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/plugins/galleriffic/js/jquery.galleriffic.js"></script>
        <script type="text/javascript" src="/plugins/galleriffic/js/jquery.opacityrollover.js"></script>
        <script type="text/javascript" src="/plugins/jquery-file-download/jquery.fileDownload.js"></script>

        <script type="text/javascript">
            document.write('<style>.noscript { display: none; }</style>');
        </script>

        <style type="text/css">

            div.caption-container{
                width: 135px;
                height: 750px;
            }

            div.slideshow {
                position:relative;
                left:0px;
            }

            div.slideshow-container{
                width: 1000px;
            }

            div.slideshow-container .image-wrapper{
                margin-left: -0px;
            }

            div.slideshow img{
                width: 850px;
            }

            body{
                background-color: #000000;
            }

            .red{
                font-weight: bold;
                color: red;
            }

            .blue{
                font-weight: bold;
            }

            a.thumb img{
                height: 105px;
                width: 74px;
            }

            span.image-wrapper img{
                height: 1100px;
                width: 850px;
            }

            .label-num-processo{
                font-weight: bold;
                margin-left: 30px;
            }

            .pagination{
                visibility: hidden;
                display: none;
            }
        </style>

    </head>
    <body>
        
        <?php foreach ($images as $image): ?>                                
        <input type="hidden" value="<?php echo $image['DIGITAL'] ?>" class="digitais">
        <?php endforeach; ?>


        <div id="page">
            <div id="container">

                <div class="navigation-container">
                    <hr>
                    <button class="button-download-image">Baixar PDF</button>
                    <?php if ($manager): ?>
                        <button class="button-alter-status-image" value="0">Reservada</button>
                        <button class="button-alter-status-image" value="1">P&uacute;blica</button>
                        <button class="button-alter-status-image" value="2">Excluir</button>
                    <?php endif; ?>
                    <hr>
                    <br>
                    <div id="thumbs" class="navigation">
                        <a class="pageLink prev" style="visibility: hidden;" href="#" title="Página Anterior"></a>

                        <ul class="thumbs noscript">

                            <?php foreach ($images as $key => $image): ?>
                                <?php
                                $lote = Imagens::factory()->generateLote($image['DIGITAL']);

                                $url = sprintf("%scache/%s/%s/", __CAM_IMAGENS__, $lote, $image['DIGITAL']);

                                //url para geração de imagem
                                $url2 = sprintf("%s/modelos/imagens/getPNG.php?digital=%s&page=", __URLSERVERAPP__, $image['DIGITAL']);
                                ?>
                                <li>
                                    <a class="thumb" title="<?php print $image['DIGITAL']; ?>" href="<?php print ($image['FLG_PUBLICO'] == 1 || $allow) ? "{$url2}{$image['MD5']}" : '/imagens/documento_confidencial_view.png'; ?>">
                                        <span  class="page-num <?php print ($image['FLG_PUBLICO'] == 1) ? 'blue' : 'red'; ?>"><?php print $key + 1; ?></span>
                                        <img src="<?php print ($image['FLG_PUBLICO'] == 1 || $allow) ? "{$url}{$image['MD5']}_thumb.png" : '/imagens/documento_confidencial_thumb.png'; ?>" alt="Página <?php print $key + 1; ?>" />
                                    </a>
                                </li>
                            <?php endforeach; ?>

                        </ul>
                        <a class="pageLink next" style="visibility: hidden;" href="#" title="Próxima Página"></a>
                    </div>
                </div>

                <div class="content">
                    <div class="slideshow-container">
                        <div id="loading" class="loader"></div>
                        <div id="slideshow" class="slideshow"></div>
                    </div>
                    <div id="caption" class="caption-container">
                        <div class="photo-index"></div>
                    </div>
                </div>

                <div style="clear: both;"></div>
            </div>

        </div>


<script type="text/javascript">

$(document).ready(function($) {

    $('div.content').css('display', 'block');

    var onMouseOutOpacity = 0.5;

    $('#thumbs ul.thumbs li, div.navigation a.pageLink').opacityrollover({
        mouseOutOpacity: onMouseOutOpacity,
        mouseOverOpacity: 1.0,
        fadeSpeed: 'fast',
        exemptionSelector: '.selected'});
        var gallery = $('#thumbs').galleriffic({
        delay: 0,
        numThumbs: 10,
        preloadAhead: 1,
        imageContainerSel: '#slideshow',
        captionContainerSel: '#caption',
        loadingContainerSel: '#loading',
        renderSSControls: false,
        renderNavControls: false,
        enableHistory: false,
        autoStart: false,
        maxPagesToShow: -1,
        syncTransitions: true,
        defaultTransitionDuration: 0,
        onSlideChange: function(prevIndex, nextIndex) {
            this.find('ul.thumbs').children()
            .eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
            .eq(nextIndex).fadeTo('fast', 1.0);
            this.$captionContainer.find('div.photo-index')
            .html('Página ' + (nextIndex + 1) + ' de ' + this.data.length);
        },
        onPageTransitionOut: function(callback) {
            this.fadeTo('fast', 0.0, callback);
        },
        onPageTransitionIn: function() {
            var prevPageLink = this.find('a.prev').css('visibility', 'hidden');
            var nextPageLink = this.find('a.next').css('visibility', 'hidden');
            if (this.displayedPage > 0)
            prevPageLink.css('visibility', 'visible');
            var lastPage = this.getNumPages() - 1;
            if (this.displayedPage < lastPage)
            nextPageLink.css('visibility', 'visible');
            this.fadeTo('fast', 1.0);
        }
    });
    gallery.find('a.prev').click(function(e) {
        gallery.previousPage();
        e.preventDefault();
    });
    gallery.find('a.next').click(function(e) {
        gallery.nextPage();
        e.preventDefault();
    });
    function pageload(hash) {
        if (hash) {
        $.galleriffic.gotoImage(hash);
        } else {
        gallery.gotoIndex(0);
        }
    }

<?php if ($manager) : ?>
    $('.button-alter-status-image').click(function() {

        var arrDigitais = [];
        $('.digitais').each(function(a, b) {
            var testDigital = $(this).val();
            if( $.inArray(testDigital, arrDigitais) == -1 ){
                arrDigitais.push( testDigital );
            }
        });

        $.post("../modelos/documentos/imagens.php", {
            acao: 'alterar-status-documento-imagem',
            digitais: arrDigitais,
            status: $(this).val()
        }, function(data) {
            if (data.success != 'true') {
                    alert('Não foi possível atualizar o status do Documento!');
            }
            location.reload();
        }, 'json');
    });
<?php endif; ?>

    $(".button-download-image").click(function() {

    if (confirm('Deseja efetuar o download do arquivo PDF?')) {
        $.fileDownload('<?php echo $urlPDF; ?>');
            return false;
        }
    });

    $('.thumb').bind('click', function(e) {
        e.preventDefault();
    });
});
            
</script>

    </body>
</html>