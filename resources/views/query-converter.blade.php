@extends('layout')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">Execute Query</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Convert MySQL Query to PostgreSQL</h5>
                </div>
                <div class="card-body">

                    <form action="{{ route('convert-query') }}" method="POST">
                        @csrf
                        <label for="query">MySQL Query:</label>
                        <textarea class="form-control mb-3" name="query" id="query" rows="5" cols="50" required></textarea>
                        <button class="btn btn-success" type="submit">Convert Query</button>
                    </form>

                    @if (session('convertedQuery'))
                    <form action="#" class="mt-3">
                        <label for="query">Converted Query:</label>
                        <div class="mb-3">
                            <textarea class="form-control" name="query" id="query" rows="5" cols="50" required>{{ session('convertedQuery') }}</textarea>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
            @if (session('result'))
                <h2>Results:</h2>
                <pre>{{ session('result') }}</pre>
            @endif
        </div>
    </div>
@endsection
