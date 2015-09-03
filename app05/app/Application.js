/**
 * The main application class. An instance of this class is created by app.js when it
 * calls Ext.application(). This is the ideal place to handle application launch and
 * initialization details.
 */

function getAjax (url) {
    // The function passed to Ext.Promise() is called immediately to start
    // the asynchronous action.
    //
    return new Ext.Promise(function (resolve, reject) {
        Ext.Ajax({
            url: url,
            success: function (response) {
                // Use the provided "resolve" method to deliver the result.
                //
                resolve(response);
            },
            failure: function (response) {
                // Use the provided "reject" method to deliver the error
                //
                reject(response);
            }
        });
    });
}

Ext.define('app05.Application', {
    extend: 'Ext.app.Application',
    
    name: 'app05',
    requires:[
        'app05.classes.Configuracion',
        'app05.classes.MensajeCircular',
        'app05.classes.AjaxRemoto'
    ],

    stores: [
        // TODO: add global / shared stores here
    ],


    
    launch: function () {
        // TODO - Launch the application

        //var mensaje = Ext.create('app05.classes.MensajeCircular');
        //mensaje.show();
        
        var ajax = Ext.create('app05.classes.AjaxRemoto');
        ajax.executeAjax('app.json');

    },

    onAppUpdate: function () {
        Ext.Msg.confirm('Application Update', 'This application has an update, reload?',
            function (choice) {
                if (choice === 'yes') {
                    window.location.reload();
                }
            }
        );
    }
});