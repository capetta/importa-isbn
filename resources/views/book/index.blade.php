@extends('layouts.dash')

@section('content_dash')

<br><br>

@if (isset($message))
<div class="row">
    <div class="col-md-10" style="margin: 0 auto;">
        <div class="flash-message">
            <p class="alert alert-{{ isset($error) ? $error : 'success' }}">{{ $message }}
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            </p>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-10" style="margin: 0 auto;">
        <div class="card">
            <div class="card-header">Consultar ISBN</div>
            <div class="card-body">
                <form method="GET" action="{{ url('/book') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="" value="{{ request('search') }}">
                        <span class="input-group-append">
                            <button class="btn btn-secondary" type="submit">
                                Pesquisar
                            </button>
                        </span>
                    </div>
                </form>

                <br/>
                <br/>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>ISBN 10</td>
                                <td>
                                    {{ $book->isbn10 }}
                                </td>
                            </tr>

                            <tr>
                                <td>ISBN 13</td>
                                <td>{{ $book->isbn13 }}</td>
                            </tr>

                            <tr>
                                <td>Título</td>
                                <td>{{ $book->title }}</td>
                            </tr>

                            <tr>
                                <td>Subtítulo</td>
                                <td>{{ $book->subtitle }}</td>
                            </tr>

                            <tr>
                                <td>Autores</td>
                                <td>{{ implode(', ', json_decode($book->authors) ?? []) }}</td>
                            </tr>

                            <tr>
                                <td>Editora</td>
                                <td>{{ $book->publisher }}</td>
                            </tr>

                            <tr>
                                <td>Ano de Publicação</td>
                                <td>{{ $book->publishedDate }}</td>
                            </tr>

                            <tr>
                                <td>Descrição</td>
                                <td>{{ $book->description }}</td>
                            </tr>

                            <tr>
                                <td>Páginas</td>
                                <td>{{ $book->pageCount }}</td>
                            </tr>

                            <tr>
                                <td>Idioma</td>
                                <td>{{ $book->language }}</td>
                            </tr>

                            <tr>
                                <td>País</td>
                                <td>{{ $book->country }}</td>
                            </tr>

                            <?php $isbn = $book->isbn10 ?? $book->isbn13; ?>

                             @if (!empty($isbn))
                            <?php $url = url('book/json/' . $isbn); ?>
                            <tr>
                                <td>JSON</td>
                                <td>
                                    <a target="_blank" href="{{ $url }}">{{ $url }}</a>
                                </td>
                            </tr>

                            <?php $url = url('book/xml/' . $isbn); ?>
                            <tr>
                                <td>XML</td>
                                <td>
                                    <a target="_blank" href="{{ $url }}">{{ $url }}</a>
                                </td>
                            </tr>
                            @endif

                            <?php $providerData = $book->getProviderData(); ?>

                            <tr>
                                <td>Fonte</td>
                                <td><a target="_blank" href="{{ $providerData['url'] }}">{{ $providerData['name'] }}</a></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-center">
                        <a href="{{ url('report/create?isbn=' . $book->isbn13) }}">Informar um problema</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
