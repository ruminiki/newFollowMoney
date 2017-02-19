@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Credit Card
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($creditCard, ['route' => ['creditCards.update', $creditCard->id], 'method' => 'patch']) !!}

                        @include('creditCards.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection