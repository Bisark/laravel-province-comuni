# Migrazione Laravel per Comuni e Province Italiane

Lo scopo del progetto è quello di rendere disponibile in maniera rapida e personalizzabile un file migrazione per Laravel 5+ con tutti i comuni e le province italiane creato a partire dall'[Elenco dei codici e delle denominazioni delle unità territoriali](https://www.istat.it/it/archivio/6789#Elencodeicodiciedelledenominazionidelleunitterritoriali-0) reso disponibile dall'ISTAT.

*Ultimo aggiornamento: 28 giugno 2019*
# Utilizzo

Il modo più semplice di utilizzare il progetto è quello di scaricare il file *Y_m_d_His_province_comuni_table.php* e copiarlo all'interno della cartella del progetto Laravel *database/migrations*, sostituendo ove necessario la data presente nel nome del file.

## Comando CLI
Alternativamente è possibile generare il file di migrazione utilizzando php da linea di comando. 
Con questa metodologia è possibile personalizzare anche i nomi dei campi e delle tabelle della migrazione, come mostrato di seguito.

### Parametri disponibili

|OGGETTO            |PARAMETRO                      |DEFAULT                      |
|----------------|-------------------------------|-------------------------------|
|Tabella province|`province`|province
|Tabella comuni|`comuni`|comuni
|Campo _nome_|`nome`|nome
|Campo _codice_ provincia|`codice`|codice
|Campo foreign key per provincia |`provincia_id`|provincia_id

Esempio:

     php generateMigration.php --comuni=cities --nome=name

Risultato

    Schema::create('cities', function(Blueprint \$table){
    			 \$table->tinyInteger('id')->unsigned();
    	 		 \$table->string('nome');
				 ...


### Possibili problematiche

Se si riscontrano errori di _Class Not Found_ durante la migrazione o il rollback, eseguire il comando `composer dump-autoload`.