/**
 * This class is the controller for the main view for the application. It is specified as
 * the "controller" of the Main view class.
 *
 * TODO - Replace this content of this view to suite the needs of your application.
 */
Ext.define('app13.view.main.MainController', {
    extend: 'Ext.app.ViewController',

    alias: 'controller.main',

    onItemSelected: function (sender, record) {
    	
    	//var url = record.get();
    	
        //Ext.Msg.confirm('Confirmar Imagen', 'Deseas ver la imagen', 'onConfirm', this);
    },

    onConfirm: function (choice) {
        if (choice === 'yes') {            
            
        }
    },
    onActivateMainList: function(me,opts){
    	var list=me.down('#imagenes');
    	try{
    		list.getStore().load();
    	}catch(ex){}
    	try{
    		list.getStore().refresh();
    	}catch(ex){}
    	
    }
});
