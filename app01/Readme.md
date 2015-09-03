# app01/app

This folder contains the javascript files for the application.

# app01/resources

This folder contains static resources (typically an `"images"` folder as well).

# app01/overrides

This folder contains override classes. All overrides in this folder will be 
automatically included in application builds if the target class of the override
is loaded.

# app01/sass/etc

This folder contains misc. support code for sass builds (global functions, 
mixins, etc.)

# app01/sass/src

This folder contains sass files defining css rules corresponding to classes
included in the application's javascript code build.  By default, files in this 
folder are mapped to the application's root namespace, 'app01'. The
namespace to which files in this directory are matched is controlled by the
app.sass.namespace property in app01/.sencha/app/sencha.cfg. 

# app01/sass/var

This folder contains sass files defining sass variables corresponding to classes
included in the application's javascript code build.  By default, files in this 
folder are mapped to the application's root namespace, 'app01'. The
namespace to which files in this directory are matched is controlled by the
app.sass.namespace property in app01/.sencha/app/sencha.cfg. 
