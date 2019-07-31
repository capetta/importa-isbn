<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Report;

class ReportController extends Controller
{
	public function create(Request $request)
    {
        $report = new Report;
        $report->isbn = $request->get('isbn') ?? '';

        return view('report.create', compact('report'));
    }

    public function store(Request $request)
    {
        $this->validar($request);
        $cidade = Report::create($request->all());

        return redirect('report/create')
            ->with('flash_message', "Informe de erros enviado com sucesso!");
    }

    private function validar(Request $request)
    {
        $regras = [
            'isbn' => 'required|isbnsize|numeric',
            'problem' => 'required'
        ];

        $mensagens = [
            'required' => 'Campo obrigatório.',
            'isbnsize' => 'Informe o ISBN de 10 ou 13 dígitos',
            'numeric' => 'Informe apenas números'
        ];

        Validator::extend('isbnsize', function ($attribute, $value, $parameters, $validator)
        {
            return strlen($value) == 10 || strlen($value) == 13;
        });

        $this->validate($request, $regras, $mensagens);
    }
}
