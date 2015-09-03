clear
rm -rf app01
echo "El comando de sencha cmd creara app01 como proyecto de Sencha ExtJS 4.2"
sencha -sd /home/guest/sencha/framework/ext-4.2.1.883/ generate app  app01 app01
echo "Finalizo generacion de proyecto"