@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Movement
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('movements.show_fields')
                    {{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default']) }}
                </div>
            </div>
        </div>
    </div>
@endsection
