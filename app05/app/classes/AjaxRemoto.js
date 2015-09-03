Ext.define(
    'app05.classes.AjaxRemoto', 
    {
    	requires: [
    		'Ext.Promise'
    	],
		executeAjax : function(url){
			// llamada de ajax usando promise
			// esto nos permite ser mas ordenados en el trabajo con codigo fuente con ajax
    		var promise = Ext.Ajax.request(
	    		{
				    url: url,
				}
			);
			// funcion futura del promise
			promise.then(
				function (response) {
					console.log(response.responseText);
			    	Ext.Msg.alert("Mensaje",response.responseText);
				}
			);
    		
    	}
    }
);