/**
 * This class is the main view for the application. It is specified in app.js as the
 * "mainView" property. That setting automatically applies the "viewport"
 * plugin causing this view to become the body element (i.e., the viewport).
 *
 * TODO - Replace this content of this view to suite the needs of your application.
 */
Ext.define('app12.view.main.Main', {
    extend: 'Ext.tab.Panel',
    xtype: 'app-main',

    requires: [
        'Ext.plugin.Viewport',
        'Ext.window.MessageBox',
        'Ext.panel.Panel',

        'app12.view.main.MainController',
        'app12.view.main.MainModel',
        'app12.view.main.List',
        'app12.store.UsuarioStore'
    ],

    controller: 'main',
    viewModel: 'main',

    ui: 'navigation',

    tabBarHeaderPosition: 1,
    titleRotation: 0,
    tabRotation: 0,

    header: {
        layout: {
            align: 'stretchmax'
        },
        title: {
            bind: {
                text: '{name}'
            },
            flex: 0
        },
        iconCls: 'fa-th-list'
    },

    tabBar: {
        flex: 1,
        layout: {
            align: 'stretch',
            overflowHandler: 'none'
        }
    },

    responsiveConfig: {
        tall: {
            headerPosition: 'top'
        },
        wide: {
            headerPosition: 'left'
        }
    },

    defaults: {
        bodyPadding: 20,
        tabConfig: {
            plugins: 'responsive',
            responsiveConfig: {
                wide: {
                    iconAlign: 'left',
                    textAlign: 'left'
                },
                tall: {
                    iconAlign: 'top',
                    textAlign: 'center',
                    width: 120
                }
            }
        }
    },

    items: [
	    {
	        title: 'Inicio',
	        iconCls: 'fa-home',
	        layout: 'border',
		    padding: 5,
		    listeners: {
        		activate: 'onActivateInicio'
    		},
	        // The following grid shares a store with the classic version's grid as well!
	        items: [
		        {
		            xtype: 'mainlist',
		            itemId: 'listado',
		            title: 'Listado de usuarios',
		            region: 'north',
		            height: 250
		        },
		        {
		        	xtype: 'panel',
		        	title: 'Usuario',
		        	region: 'center'
		        	
		        }
	        ]
	    }, {
	        title: 'Crear Usuario',
	        iconCls: 'fa-users',
	        layout: 'border',
	        padding: 5,
		    listeners: {
        		activate: 'onActivateCreacion'
    		},
	        items: [
		        {
		            xtype: 'mainlist',
		            itemId: 'creacion',
		            title: 'Crear usuarios',
		            region: 'north',
		            height: 250
		        }
	        ]        
	    }, {
	        title: 'Editar Usuario',
	        iconCls: 'fa-users',
	        layout:'border',
	        padding: 5,
	        listeners: {
        		activate: 'onActivateEdicion'
    		},
	        items: [
		        {
		            xtype: 'mainlist',
		            itemId: 'edicion',
		            title: 'Editar usuarios',
		            region: 'north',
		            height: 250
		        }
	        ]
	    }, {
	        title: 'Eliminar Usuario',
	        iconCls: 'fa-users',
	        layout: 'border',
	        padding: 5,
	        listeners: {
        		activate: 'onActivateEliminacion'
    		},
	        items: [
		        {
		            xtype: 'mainlist',
		            itemId: 'eliminacion',
		            title: 'Eliminar usuarios',
		            region: 'north',
		            height: 250
		        }
	        ]
	    }, {
	        title: 'Ayuda',
	        iconCls: 'fa-cog',
	        padding: 5,
	        bind: {
	            html: '{help}'
	        }
	    }
    ]
});
