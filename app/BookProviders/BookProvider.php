<?php

namespace App\BookProviders;

use App\Book;
use App\BookProviders\GoogleBookProvider;

class BookProvider
{
    public static function getBook($isbn)
    {
        $localProviders = ['local', 'ol'];

        foreach ($localProviders as $provider)
        {
            $book = Book::getBookCache($provider, $isbn);

            if (!is_null($book))
            {
                return $book;
                exit;
            }
        }

        $book = GoogleBookProvider::getBook($isbn);

        return $book;
    }
}
