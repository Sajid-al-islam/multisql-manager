@extends('layout')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">Execute Query</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Run Raw Query on MySQL and PostgreSQL</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('run-query') }}" method="POST">
                        @csrf
                        <label for="mquery">MySql Query:</label>
                        <div class="mb-3">
                            <textarea class="form-control" name="mysql_query" id="mquery" rows="5" cols="50"></textarea>
                        </div>

                        <label for="pquery">PgSql Query:</label>
                        <div class="mb-3">
                            <textarea class="form-control" name="pgsql_query" id="pquery" rows="5" cols="50"></textarea>
                        </div>

                        <button class="btn btn-success" type="submit">Run Query</button>
                    </form>
                </div>
            </div>
            @if (session('result'))
                <h2>Results:</h2>
                <div>
                    <h3>MySQL:</h3>
                    @if (isset(session('result')['mysql']))
                        @foreach (session('result')['mysql'] as $mysqlResult)
                        <pre>{{ $mysqlResult }}</pre>
                        @endforeach
                    @endif
                </div>
                <div>
                    <h3>PostgreSQL:</h3>
                    @if (isset(session('result')['pgsql']))
                        @foreach (session('result')['pgsql'] as $pgsqlResult)
                            <pre>{{ $pgsqlResult }}</pre>
                        @endforeach
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
