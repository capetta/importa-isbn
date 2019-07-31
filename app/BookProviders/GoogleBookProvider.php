<?php

namespace App\BookProviders;

use App\Book;

class GoogleBookProvider
{
    public static function getBook($isbn)
    {
        $jsonObj = json_decode(file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn"));
        $item = property_exists($jsonObj, 'items') ? $jsonObj->items[0] : null;

        if (is_null($item))
            return null;

        $item2 = null;

        if (!empty($item->selfLink)){
            $item2 = json_decode(file_get_contents($item->selfLink));
        }
        
        return self::createBook($item, $item2);
    }

    private static function createBook($item, $item2)
    {
        $book = new Book;
        $book->provider = 'google';
        $book->provider_id = $item->id ?? null;
        $book->title = $item->volumeInfo->title ?? $item2->volumeInfo->title ?? null;
        $book->subtitle = $item->volumeInfo->subtitle ?? $item2->volumeInfo->subtitle ?? null;
        //TODO: $book->authors = str_replace(']"', ']', str_replace('"[', '[', json_encode($item->volumeInfo->authors ?? null)));
        $book->publisher = $item->volumeInfo->publisher ?? $item2->volumeInfo->publisher ?? null;
        $book->publishedDate = $item->volumeInfo->publishedDate ?? $item2->volumeInfo->publishedDate ?? null;
        $book->description = $item->volumeInfo->description ?? $item2->volumeInfo->description ?? null;
        $book->isbn10 = self::getIsbn($item, 'ISBN_10');
        $book->isbn13 = self::getIsbn($item, 'ISBN_13');
        $book->pageCount = $item->volumeInfo->pageCount ?? $item2->volumeInfo->pageCount ?? null;
        $book->printType = $item->volumeInfo->printType ?? $item2->volumeInfo->printType ?? null;
        $book->language = $item->volumeInfo->language ?? $item2->volumeInfo->language ?? null;
        $book->country = $item->saleInfo->country ?? $item2->saleInfo->country ?? null;
        

        return $book;
    }

    private static function getIsbn($item, $type)
    {
        $arrayIsbn = $item->volumeInfo->industryIdentifiers ?? [];

        foreach ($arrayIsbn as $item)
            if ($item->type === $type) return $item->identifier;
    }
}
