jQuery.fn.dataTableExt.oApi.fnFilterOnEnter = function ( oSettings ) {

 /*
  * Type:        Plugin for DataTables (www.datatables.net) JQuery plugin.
  * Name:        dataTableExt.oApi.fnFilterOnEnter
  * Version:     1.0.0
  * Description: Instead of filtering based on a delay, submit the filter only
  *              when submitted by the enter key.
  * Inputs:      object:oSettings - dataTables settings object
  * Returns:     JQuery
  * Usage:       $('#example').dataTable().fnFilterOnEnter();
  *
  * Author:      Jeff Whitmire (www.jwhitmire.com)
  * Created:     8/4/2010
  * Language:    Javascript
  * License:     GPL v2 or BSD 3 point style
  * Contact:     jeff@jwhitmire.com
  */


  var _that = this;

  this.each(function(i) {
    $.fn.dataTableExt.iApiIndex = i;
    var $this = this;
    var sPreviousSearch = null;
    var anControl = $('input', _that.fnSettings().aanFeatures.f);
    
    anControl.unbind('keyup').bind('keyup',function() {

      /* Update the filter input elements for the new display */
      for ( var i=0, iLen=anControl.length ; i<iLen ; i++ )
      {
        if ( anControl[i] != this.parentNode )
        {
          $('input', anControl[i]).val( this.value );
        }
      }

      /* Skip the filter submission from the original */
    });

    anControl.unbind( 'keypress' ).bind( 'keypress', function(e) {

      /* Do the filtering only if the enter key was pressed. */
      if ( e.keyCode == 13 )
      {
        
        if (sPreviousSearch === null || sPreviousSearch != anControl.val()) {
          sPreviousSearch = anControl.val();
          _that.fnFilter( anControl.val() );
        }
        return false;
      }
    });
  });

  return this;
}