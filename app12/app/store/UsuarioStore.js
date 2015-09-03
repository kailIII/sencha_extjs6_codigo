Ext.define(
	'app12.store.UsuarioStore',
	{
		extend: 'Ext.data.Store',
		model: 'app12.model.UsuarioModel',
		alias: 'store.usuario',
		proxy: {
			type:'ajax',
			api:{
				create: 	'source/index.php/usuario/save',
				read:		'source/index.php/usuario/all',
				update: 	'source/index.php/usuario/update',
				destroy: 	'source/index.php/usuario/delete'
			},
			reader:{
				type: 'json',
				rootProperty: 'data'
			},
			writer: {
				type:'json',
				rootProperty: 'data',
				writeAllFields: true,
				encode: true,
				allowSingle: true
			}
		},
		autoLoad: true,
		autoSync: true
	}
);
