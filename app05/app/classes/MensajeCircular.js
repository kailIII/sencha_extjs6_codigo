// clase mensajecircular 
Ext.define(
    'app05.classes.MensajeCircular', 
    {
	    requires:[
	        'app05.classes.Configuracion'
	    ],
	    inicio: function(){
	        Ext.Msg.alert("Mensaje","Iniciando App");
	    },
	    hola:function(){
	        Ext.Msg.alert("Mensaje",app05.classes.Configuracion.MENSAJE_HOLA);
	    },
	    creador:function(){
	        Ext.Msg.alert("Mensaje","Mi creador es : "+app05.classes.Configuracion.CREADOR);            
	    },
	    fin:function(){
	        Ext.Msg.alert("Mensaje","Finalizando App");
	    },
	    show:function(){
	    	var me=this;
	
	        var secuencia=0;
	        
	        var runner = new Ext.util.TaskRunner(),
	            updateMensaje,task;
	
	        
	        updateMensaje = function() {
	            if(secuencia==0){
	                me.inicio();                
	            }
	            if(secuencia==1){
	                me.hola();
	            }
	            if(secuencia==2){
	                me.creador();
	            }
	            if(secuencia==3){
	                me.fin();
	            }
	            secuencia++;
	            if(secuencia%3==0){
	                secuencia=0;
	            }
	        };
	
	        task = runner.start(
	            {
	                run: updateMensaje,
	                interval: 500
	            }
	        );
	    	
	    }
    }
);