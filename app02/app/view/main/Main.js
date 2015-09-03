/**
 * This class is the main view for the application. It is specified in app.js as the
 * "autoCreateViewport" property. That setting automatically applies the "viewport"
 * plugin to promote that instance of this class to the body element.
 *
 * TODO - Replace this content of this view to suite the needs of your application.
 */
Ext.define('app02.view.main.Main', {
    extend: 'Ext.container.Container',
    requires: [
        'app02.view.main.MainController',
        'app02.view.main.MainModel'
    ],

    xtype: 'app-main',
    
    controller: 'main',
    viewModel: {
        type: 'main'
    },

    layout: {
        type: 'border'
    },

    items: [{
        xtype: 'panel',
        bind: {
            title: '{name}'
        },
        region: 'west',
        html: '<ul><li>This area is commonly used for navigation, for example, using a "tree" component.</li></ul>',
        width: 250,
        split: true,
        tbar: [{
            text: 'Button',
            handler: 'onClickButton'
        }]
    },{
        region: 'center',
        xtype: 'tabpanel',
        items:[
        {
            title: 'Tab Panel Numero 1',
            html: '<h2>Panel Numero 1.</h2>'
        },
        {
            title: 'Tab Panel Numero 2',
            html: '<h2>Panel Numero 2.</h2>'
        },
        {
            title: 'Tab Panel Numero 3',
            html: '<h2>Panel Numero 3.</h2>'
        },
        {
            title: 'Tab Panel Numero 4',
            html: '<h2>Panel Numero 4.</h2>'
        }
        ]
    }]
});
