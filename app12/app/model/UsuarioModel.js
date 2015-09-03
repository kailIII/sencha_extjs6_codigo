/**
 * @author guest
 */
Ext.define(
	'app12.model.UsuarioModel',
	{
		extend: 'Ext.data.Model',
		fields:
			[
				{
					name:'id',
					type:'int'
				},
				{
					name:'nombre',
					type:'string'
				},
				{
					name:'email',
					type:'string'
				},
				{
					name:'usuario',
					type:'string'
				},
				{
					name:'password',
					type:'string'
				}
			]
		
	}
	
);
