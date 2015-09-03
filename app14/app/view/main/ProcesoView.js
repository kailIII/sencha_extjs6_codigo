/**
* Vista de proceso 
**/
Ext.define('app14.view.main.ProcesoView', {
    extend: 'Ext.form.Panel',
    requires:[
    	'app14.controller.ProcesoController'
    ],
    xtype: 'procesoview',
    title: 'Formulario Proceso',
    bodyPadding: 10,
    controller: 'proceso',
    frame: true,
    items: [
        {
            xtype: 'numberfield',
            name: 'primero',
            fieldLabel: 'Primer numero',
            labelWidth: 150,
            msgTarget: 'side',
            allowBlank: false,
            anchor: '100%'
        },
        {
            xtype: 'numberfield',
            name: 'segundo',
            fieldLabel: 'Segundo numero',
            labelWidth: 150,
            msgTarget: 'side',
            allowBlank: false,
            anchor: '100%'
        }
    ],

    buttons: [
        {
            text: 'Sumar',
            formBind: true, //only enabled once the form is valid
        	disabled: true,
            listeners: {
		        click: 'sumar' 
		    }
        }
    ]

});