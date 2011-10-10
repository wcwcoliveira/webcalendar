// $Id$

var validform = true;

function valid_form ( form ) {
  var err = "";
  if ( form.layercolor && ! valid_color ( form.layercolor.value ) )
    err += xlate['invalidColor']; // translate ( 'Invalid color' )

  if ( err.length > 0 ) {
    alert (xlate['noBlankURL'].replace(/XXX/, err)
      + "\n\n + xlate['formatColorRGB']; // translate ( 'Color format should be RGB' )
    return false;
  }
  if ( ! form.nurl.value ) {
    alert (xlate['noBlankURL']); // translate( 'no blank URLs' )
    return false;
  }
  check_name();

  return validform;
}

function toggle_layercolor() {
 if ( document.prefform.nlayer.checked == true) {
   makeVisible ( 'nlayercolor', true );
 } else {
   makeInvisible ( 'nlayercolor' );
 }
}

function check_name() {
  var url = 'ajax.php';
  var params = 'page=edit_remotes&name=' + $F('nid');
  var ajax = new Ajax.Request(url,
    {method: 'post',
    parameters: params,
    onComplete: showResponse});
}

function showResponse(originalRequest) {
  if (originalRequest.responseText) {
    text = originalRequest.responseText;
    // This causes javascript errors in Firefox, but these can be ignored.
    alert (text);
    document.prefform.nid.focus();
    validform = false;
  } else {
    validform = true;
  }
}
