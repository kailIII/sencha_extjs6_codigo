Ext.define('app13.controller.main.FormFileController', {
    extend: 'Ext.app.ViewController',

    alias: 'controller.formfile',

    uploadFile: function (button, e, eOpts) {
    	var form = button.up('form');
        
        if(form.isValid()){
            form.submit({
                url: 'source/index.php/file/upload/',
                waitMsg: 'Subiendo Imagen',
                success: function(fp, o) {
                	if(o.result.success == 'true'){
                		Ext.Msg.alert('Exito', 'Tu imagen "' + o.result.file + '" ha sido subida.');	
                	}else{
                		Ext.Msg.alert('Error', 'Hubo un error en el proceso');
                	}                  
                    
                    //console.log(o.result);
                }
            });
        }
    }
});
