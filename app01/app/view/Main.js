Ext.define('app01.view.Main', {
    extend: 'Ext.container.Container',
    requires:[
        'Ext.tab.Panel',
        'Ext.layout.container.Border'
    ],
    
    xtype: 'app-main',

    layout: {
        type: 'border'
    },

    items: [{
        region: 'west',
        xtype: 'panel',
        title: 'west',
        width: 150
    },{
        region: 'center',
        xtype: 'tabpanel',
        items:[
	        {
	            title: 'Tab Panel Numero 1'
	        },
	        {
	            title: 'Tab Panel Numero 2'
	        },
	        {
	            title: 'Tab Panel Numero 3'
	        },
	        {
	            title: 'Tab Panel Numero 4'
	        }
        ]
    }
    ]
});