/**
*  Proceso Controller 
**/

Ext.define(
	'app14.controller.ProcesoController', 
	{
	    extend: 'Ext.app.ViewController',
	
	    alias: 'controller.proceso',
	    
	    sumar: function (button, e, eOpts) {
	    	var form = button.up('form');
	        
	        if(form.isValid()){
	            form.submit({
	                url: 'source/socket.php',
	                waitMsg: 'Procesando ....',
	                success: function(fp, o) {
	                	if(o.result.success == 'true'){
	                		Ext.Msg.alert('Exito', 'El resultado es :"' + o.result.suma + '" ');	
	                	}else{
	                		Ext.Msg.alert('Error', 'Hubo un error en el proceso');
	                	}            
	                	try{
	                		form.clear();
	                	}catch(ex){}      
	                    
	                    //console.log(o.result);
	                }
	            });
	        }
	    }

    

	}
);