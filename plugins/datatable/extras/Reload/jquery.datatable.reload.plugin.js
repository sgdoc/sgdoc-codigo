$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource ) {
    try{
        if ( typeof sNewSource != 'undefined' ) {
            oSettings.sAjaxSource = sNewSource;
        }

        this.fnClearTable( this );
        this.oApi._fnProcessingDisplay( oSettings, true );

        $.getJSON( oSettings.sAjaxSource, null, $.proxy(function(json) {

            for ( var i=0 ; i<json.aaData.length ; i++ ) {
                this.oApi._fnAddData( oSettings, json.aaData[i] );
            }

            oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
            this.fnDraw( this );
            this.oApi._fnProcessingDisplay( oSettings, false );
        }, this));
    }catch(e){
        alert(e);
    }

}