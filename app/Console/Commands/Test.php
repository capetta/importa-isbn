<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Book;

//Importar edições
class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importol:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $inicio = new \DateTime();
        $cont = 0;
        $countries = [];
        
        

        $file = '/home/leonardo/Downloads/ol_dump_editions_2019-06-30.txt';
        $fh = fopen($file, 'r');

        // Handle failure
        if ($fh === false) {
            die('Could not open file: '.$file);
        }
        
        do
        {
            // Read a line
            $line = fgets($fh);

            // If a line was read then output it, otherwise
            // show an error
            if ($line !== false 
                && strpos($line, '8570623828') != false)
            {
                var_dump($line);
                /*$elements = preg_split("/[\t]/", $line);
                $json = json_decode($elements[4]);

                if (!is_null($json->code ?? null) && !array_key_exists($json->code, $countries))
                    $countries[$json->code] = $json->name;*/
            }
        } while ($line != false);

        // Close the file handle; when you are done using a
        // resource you should always close it immediately
        if (fclose($fh) === false) {
            echo('Could not close file: '.$file);
        }

        $diferenca = $inicio->diff(new \DateTime());
        var_dump($diferenca);

        dd($countries);
    }
}

/*


    type - type of record (/type/edition, /type/work etc.)
    key - unique key of the record. (/books/OL1M etc.)
    revision - revision number of the record
    last_modified - last modified timestamp
    JSON - the complete record in JSON format
*/

/*
array(33) {
  [0]=>
  string(12) "/type/author"*
  [1]=>
  string(13) "/type/edition"*
  [2]=>
  string(10) "/type/i18n"
  [3]=>
  string(10) "/type/type"
  [4]=>
  string(14) "/type/property"
  [5]=>
  string(19) "/type/backreference"
  [6]=>
  string(16) "/type/collection"
  [7]=>
  string(12) "/type/delete"
  [8]=>
  string(10) "/type/work"*
  [9]=>
  string(14) "/type/redirect"
  [10]=>
  string(10) "/type/page"
  [11]=>
  string(15) "/type/usergroup"
  [12]=>
  string(16) "/type/permission"
  [13]=>
  string(11) "/type/macro"
  [14]=>
  string(11) "/type/about"
  [15]=>
  string(14) "/type/template"
  [16]=>
  string(13) "/type/content"
  [17]=>
  string(9) "/type/doc"
  [18]=>
  string(15) "/type/i18n_page"
  [19]=>
  string(14) "/type/language"
  [20]=>
  string(10) "/type/user"
  [21]=>
  string(12) "/type/volume"
  [22]=>
  string(10) "/type/home"
  [23]=>
  string(13) "/type/rawtext"
  [24]=>
  string(12) "/type/object"
  [25]=>
  string(19) "/type/scan_location"
  [26]=>
  string(12) "/type/series"
  [27]=>
  string(17) "/type/scan_record"
  [28]=>
  string(9) "/type/uri"
  [29]=>
  string(13) "/type/subject"
  [30]=>
  string(11) "/type/place"
  [31]=>
  string(13) "/type/library"
  [32]=>
  string(14) "/type/local_id"
}

*/