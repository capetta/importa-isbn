<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Book;
use App\Support\ResourceImporter;
use Carbon\Carbon;

//Importar edições
class ImportOLEditions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importol:editions {file}';

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
        $cont = 1;
        $rows = [];
        

        $file = $this->argument('file');
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
                //&& strpos($line, '/type/edition') === 0 //TODO: retirar segunda condição
                && $this->isBrazil($line))
            {
                $elements = preg_split("/[\t]/", $line);
                $type = $elements[0];
                $key = $elements[1];
                $json = json_decode($elements[4]);

                $rows[] = $this->createBookArray($key, $json);

                $cont++;

                if ($cont > 1000)
                {
                  ResourceImporter::insertOrUpdate('books', $rows, ['updated_at']);

                  $cont = 0;
                  $rows = [];
                }

            }
        } while ($line != false);

        // Close the file handle; when you are done using a
        // resource you should always close it immediately
        if (fclose($fh) === false) {
            echo('Could not close file: '.$file);
        }

        dd("Dados importados com sucesso!");
    }

    private function isBrazil($line)
    {
        return strpos($line, '"publish_country": "bl "') !== false
            && (strpos($line, 'isbn_10') !== false || strpos($line, 'isbn_13') !== false );
    }

    private function createBookArray($key, $json)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        return
        [
          'provider' => 'ol',
          'provider_id' => trim(explode('/books/', $key)[1]),
          'title' => $json->title ?? null,
          /*'authors' => TODO: */
          'publisher' => implode(', ', $json->publishers ?? []) ?? null,
          'publishedDate' => $json->publish_date ?? null,
          'description' => $json->notes->value ?? null,
          'isbn10' => substr(trim($json->isbn_10[0] ?? null), 0, 10) ?? null,
          'isbn13' => substr(trim($json->isbn_13[0] ?? null), 0, 13) ?? null,
          'pageCount' => $json->number_of_pages ?? null,
          'language' => explode('/languages/', $json->languages[0]->key ?? null)[1] ?? null,
          'country' => trim($json->publish_country) ?? null,
          'created_at' => $now,
          'updated_at' => $now
        ];        
    }

    private function saveBook($key, $json)
    {
        $provider_id = trim(explode('/books/', $key)[1]);

        $isbn = $json->isbn_10[0] ?? $json->isbn_13[0] ?? null;

        $book = Book::getBookCache('ol', $isbn);

        if (is_null($book))
        {
          $book = new Book;
          $book->provider = 'ol';
          //devido a dados duplicados, não usei id da ol
        }

        $book->provider_id = $provider_id;
        $book->title = $json->title ?? null;
        /*$book->authors = TODO */
        $book->publisher = implode(', ', $json->publishers ?? []) ?? null;
        $book->publishedDate = $json->publish_date ?? null;
        $book->description = $json->notes->value ?? null;
        $book->isbn10 = $json->isbn_10[0] ?? null;
        $book->isbn13 = $json->isbn_13[0] ?? null;
        $book->pageCount = $json->number_of_pages ?? null;
        $book->language = explode('/languages/', $json->languages[0]->key ?? null)[1] ?? null;
        $book->country = trim($json->publish_country) ?? null;
        $book->save();
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