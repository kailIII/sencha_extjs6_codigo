Ext.define(
	'app13.store.ImagenStore',
	{
		extend: 'Ext.data.Store',
		model: 'app13.model.ImagenModel',
		alias: 'store.imagen',
		proxy: {
			type:'ajax',
			api:{
				create: 	'source/index.php/file/create',
				read:		'source/index.php/file/all',
				update: 	'source/index.php/file/update',
				destroy: 	'source/index.php/file/delete'
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