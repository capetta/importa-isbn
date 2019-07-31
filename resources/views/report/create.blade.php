@extends('layouts.dash')

@section('content_dash')

<br><br>

@if(Session::has('flash_message'))
<div class="row">
    <div class="col-md-6" style="margin: 0 auto;">
        <div class="flash-message">
            <p class="alert alert-{{ Session::has('erro') ? Session::get('erro') : 'success'}}">{{ Session::get('flash_message') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </p>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-6" style="margin: 0 auto;">
        <div class="card">
            <div class="card-header">Informar um problema</div>
            <div class="card-body">
                {!! Form::model($report, ['url'=> array('report'), 'method'=> 'POST']) !!}

                <div class="form-group">
                    <div class="col-md-12">
                        <label for="isbn" class="control-label">{{ 'ISBN' }}</label>
                        {!! Form::text('isbn', $report->isbn, [ 'class' => 'form-control ' . ($errors->has('isbn') ? 'is-invalid' : ''), 'tabindex' => 2]) !!}
                        {!! $errors->first('isbn', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <label for="isbn" class="control-label">{{ 'Informe o problema' }}</label>
                        {!! Form::textarea('problem', null, [ 'class' => 'form-control ' . ($errors->has('problem') ? 'is-invalid' : ''), 'tabindex' => 2]) !!}
                        {!! $errors->first('problem', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">Enviar</button>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
