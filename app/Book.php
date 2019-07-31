<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'books';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable =
    [
    	'provider',
        'provider_id',
    	'title',
        'subtitle',
    	'authors',
    	'publisher',
    	'publishedDate',
    	'description',
    	'isbn10',
    	'isbn13',
    	'pageCount',
    	'height',
    	'width',
    	'length',
    	'printType',
    	'language',
    	'country',
    	'cover'
    ];

    public static function getBookCache($provider, $isbn)
    {
        return Book::where('provider', $provider)
            ->whereRaw("(isbn10 = '$isbn' OR isbn13 = '$isbn')")
            ->first();
    }

    public function getProviderData()
    {
        $providers =
        [
            null => ['name' => '', 'url' => '#'],
            'local' => ['name' => 'Local', 'url' => '#'],
            'ol' => ['name' => 'Open Library', 'url' => 'https://openlibrary.org/books/{PROVIDER_ID}'],
            'google' => ['name' => 'Google Books', 'url' => 'https://books.google.com.br/books?id={PROVIDER_ID}']
        ];

        $provider = $providers[$this->provider];
        $provider['url'] = str_replace('{PROVIDER_ID}', $this->provider_id, $provider['url']);

        return $provider;
    }
}
