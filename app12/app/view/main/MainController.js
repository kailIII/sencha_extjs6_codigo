/**
 * This class is the controller for the main view for the application. It is specified as
 * the "controller" of the Main view class.
 *
 * TODO - Replace this content of this view to suite the needs of your application.
 */
Ext.define('app12.view.main.MainController', {
    extend: 'Ext.app.ViewController',

    alias: 'controller.main',

    onItemSelected: function (sender, record) {
        Ext.Msg.confirm('Confirm', 'Are you sure?', 'onConfirm', this);
    },
    onActivateInicio: function(me,opts){
    	var list=me.down('#listado');
    	try{
    		list.getStore().load();
    	}catch(ex){}
    	try{
    		list.getStore().refresh();
    	}catch(ex){}
    },
    onActivateCreacion: function(me,opts){
    	var list=me.down('#creacion');
    	try{
    		list.getStore().load();
    	}catch(ex){}
    	try{
    		list.getStore().refresh();
    	}catch(ex){}
    	
    },
    onActivateEdicion: function(me,opts){
    	var list=me.down('#edicion');
    	try{
    		list.getStore().load();
    	}catch(ex){}
    	try{
    		list.getStore().refresh();
    	}catch(ex){}
    	
    },
    onActivateEliminacion: function(me,opts){
    	var list=me.down('#eliminacion');
    	try{
    		list.getStore().load();
    	}catch(ex){}
    	try{
    		list.getStore().refresh();
    	}catch(ex){}
    	
    },

    onConfirm: function (choice) {
        if (choice === 'yes') {
            //
        }
    }
});
