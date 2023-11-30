function presiAjaxGetWithId( pid, action ){
    if( !pid ){
        return null;
    }

    var result;
    var sendData = {
        action: action,
        id: pid,
    };

    jQuery.ajax ( {
            type		:	'get',
            data        :   sendData,
            url			: 	PRESI_AJAX_URL,
            dataType	: 	'json',
            async       :   false,
            success		: 	function( response ){
                result = presiAjaxResponseValidate( response );
                if( result ){
                    var presidium = response.presidium;
                    result = response.presidium;
                }
            },
            error: function( response ){
                alert( JSON.stringify( response ) );
                result = null;
            }
     } );

    return result;
}

function presiAjaxGet( action ){
    var result;
    var sendData = {
        action: action
    };

    jQuery.ajax( {
            type		:	'get',
            data        :   sendData,
            url			: 	PRESI_AJAX_URL,
            dataType	: 	'json',
            async       :   false,
            success		: 	function( response ){
                result = presiAjaxResponseValidate( response );
                if( result ){
                    var presidium = response.presidium;
                    result = response.presidium;
                }
            },
            error: function( response ){
                alert( JSON.stringify( response ) );
                result = null;
            }
    });

    return result;
}

function presiAjaxSave( data, action ){
    if( !data ){
        return null;
    }

    var result;
    var sendData = {
        action: action,
        data: JSON.stringify( data ),
    };

    jQuery.ajax ( {
        type		:	'post',
        data        :   sendData,
        url			: 	PRESI_AJAX_URL,
        dataType	: 	'html',
        async       :   false,
        success		:   function( response ){
            try{
                result = JSON.parse( response );
                result = presiAjaxResponseValidate( result );
            }catch( error ){
                result = null;
            }
        },
        error: function( response ){
            alert(JSON.stringify( response ));
            result = null;
        }
    } );

    return result;
}

//Helper functions
function presiAjaxResponseValidate( response ){
    if( !response ) return null;

    if( response.status != 'success' ){
        alert( JSON.stringify( response.errormsg ) );
        return null;
    }

    return response;
}
