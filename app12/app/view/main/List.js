/**
 * This view is an example list of people.
 */
Ext.define('app12.view.main.List', {
    extend: 'Ext.grid.Panel',
    xtype: 'mainlist',

    requires: [
        'app12.store.UsuarioStore'
    ],

    title: 'Usuarios',

    store: {
        type: 'usuario'
    },

    columns: [
        { text: 'Id',  dataIndex: 'id' },
        { text: 'Nombre', dataIndex: 'nombre', flex: 1 },
        { text: 'Email', dataIndex: 'email', flex: 1 },
        { text: 'Usuario', dataIndex: 'usuario', flex: 1 }
    ],

    listeners: {
        select: 'onItemSelected'
    }
});
