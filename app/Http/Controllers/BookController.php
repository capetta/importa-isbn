<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use App\BookProviders\GoogleBookProvider;
use App\BookProviders\BookProvider;
use Validator;

class BookController extends Controller
{
	public function json($isbn)
    {
        return response((BookProvider::getBook($isbn) ?? new Book)->toJson(), 200)
            ->header('Content-Type', 'application/json');
    }

    public function xml($isbn)
    {
        $data = (BookProvider::getBook($isbn) ?? new Book)->toArray();

        // creating object of SimpleXMLElement
        $xml_data = new \SimpleXMLElement('<?xml version="1.0"?><book></book>');

        // function call to convert array to xml
        $this->array_to_xml($data, $xml_data);

        return response($xml_data->asXml(), 200)
            ->header('Content-Type', 'text/xml');
    }

    private function array_to_xml($data, &$xml_data )
    {
        foreach( $data as $key => $value ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            if( is_array($value) ) {
                $subnode = $xml_data->addChild($key);
                array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
         }
     }

    public function index(Request $request)
    {
    	$isbn = $request->get('search');

        if (empty($isbn))
        {
            return view('book.index')
                ->with('book', new Book);
        }

        if (!is_numeric($isbn))
        {
            return view('book.index')
                ->with('book', new Book)
                ->with('error', 'danger')
                ->with('message', 'Informe apenas números');
        }

    	$book = new Book;

    	if (!empty($isbn))
    		$book = BookProvider::getBook($isbn);

        if (is_null($book))
        {
            return view('book.index')
                ->with('book', new Book)
                ->with('error', 'danger')
                ->with('message', 'Livro não encontrado');
        }

        return view('book.index')
    		->with('book', $book ?? new Book);
    }

    public function report(Request $request)
    {
        return view('book.report')
            ->with('isbn', $request->get('isbn') ?? '');
    }

    public function reportPost(Request $request)
    {
        $this->validar($request);


    }

    private function validar(Request $request)
    {
        $regras = [
            'isbn' => 'required|isbnsize|integer',
            'problem' => 'required'
        ];

        $mensagens = [
            'required' => 'Campo obrigatório.',
            'isbnsize' => 'Informe o ISBN de 10 ou 13 dígitos',
            'integer' => 'Informe apenas números'
        ];

        Validator::extend('isbnsize', function ($attribute, $value, $parameters, $validator)
        {
            return strlen($value) == 10 || strlen($value) == 13;
        });

        $this->validate($request, $regras, $mensagens);
    }
}
