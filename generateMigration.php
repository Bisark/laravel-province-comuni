<?php

$comuni = [];
$province = [];


$opts  = getopt(null, [
    // "required:",     // Required value
    "comuni::",    // Optional value
    "province::",        // No value
    "nome::",
    "codice::",
    "provincia_id::"
    ]);


$comuniTableName = isset($opts['comuni']) ? $opts['comuni'] : 'comuni';
$provinceTableName = isset($opts['province']) ? $opts['province'] : 'province';
$nomeColumnName = isset($opts['nome']) ? $opts['nome'] : 'nome';
$codiceColumnName = isset($opts['codice']) ? $opts['codice'] : 'codice';
$provinciaIdColumnName = isset($opts['provincia_id']) ? $opts['provincia_id'] : 'provincia_id';


$handle = fopen("elenco-codici.csv", "r");
$row=0;
while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
    $row++;
    if($row === 1)
        continue;
    // $columns = count($data);
    // var_dump(($data));exit;
    $comuni[] = ['id' => intval($data[4]), 'nome' => utf8_encode($data[5]), 'provincia_id' => intval($data[2])];

    if(!isset($province[$data[2]]))
        $province[$data[2]] = ['id' => intval($data[2]), 'nome' => utf8_encode($data[11]), 'codice' => $data[13]];

    $row++;
    
}
fclose($handle);


$body = <<<BISARK
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProvinceComuniTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('{$provinceTableName}', function(Blueprint \$table)
		{
			\$table->tinyInteger('id')->unsigned();
            \$table->string('{$nomeColumnName}');
            \$table->string('{$codiceColumnName}')->unique();

            \$table->primary('id');	
        });
        

		Schema::create('{$comuniTableName}', function(Blueprint \$table)
		{
			\$table->smallInteger('id')->unsigned();
            \$table->string('{$nomeColumnName}');
            \$table->tinyInteger('{$provinciaIdColumnName}')->unsigned();

            \$table->primary('id');	
            \$table->foreign('{$provinciaIdColumnName}')
                    ->references('id')
                    ->on('{$provinceTableName}');
        });
        
BISARK;


$body .='\DB::table("'. $provinceTableName .'")->insert([';
foreach($province as $provincia)
{
    $body .='["id" => "'.$provincia['id'].'", "'. $nomeColumnName .'" => "'.$provincia['nome'].'", "'. $codiceColumnName .'" => "'.$provincia['codice'].'"],';
    
}

$body .=']);' . PHP_EOL;


$body .='\DB::table("'. $comuniTableName .'")->insert([';
foreach($comuni as $comune)
{
    $body .='["id" => "'.$comune['id'].'", "'. $nomeColumnName .'" => "'.$comune['nome'].'", "'. $provinciaIdColumnName .'" => "'.$comune['provincia_id'].'"],';
}

$body .=']);' . PHP_EOL;

$body .= <<<BISARK
    
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::drop('{$comuniTableName}');
		Schema::drop('{$provinceTableName}');
	}

}

BISARK;


file_put_contents(date('Y_m_d_His').'_province_comuni_table.php', $body);
