/**
 * @author guest
 */
Ext.define(
	'app13.model.ImagenModel',
	{
		extend: 'Ext.data.Model',
		fields:
			[
				{
					name:'id',
					type:'int'
				},
				{
					name:'name',
					type:'string'
				},
				{
					name:'url',
					type:'string'
				}
			]
		
	}
	
);
