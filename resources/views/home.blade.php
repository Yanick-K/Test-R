@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body overflow-auto">
                  <div id="app"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
