
Ext.define('app13.view.main.FormFile', {
    extend: 'Ext.form.Panel',
    requires:[
    	'app13.controller.main.FormFileController'
    ],
    xtype: 'formfile',
    title: 'Formulario de Imagen',
    bodyPadding: 10,
    controller: 'formfile',
    frame: true,
    items: [
        {
            xtype: 'filefield',
            name: 'imagen',
            fieldLabel: 'Imagen',
            labelWidth: 50,
            msgTarget: 'side',
            allowBlank: false,
            anchor: '100%',
            buttonText: 'Seleccionar Imagen'
        }
    ],

    buttons: [
        {
            text: 'Upload',
            formBind: true, //only enabled once the form is valid
        	disabled: true,
            listeners: {
		        click: 'uploadFile' 
		    }
        }
    ]

});