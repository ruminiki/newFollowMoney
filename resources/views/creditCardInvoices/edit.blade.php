@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Credit Card Invoice
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($creditCardInvoice, ['route' => ['creditCardInvoices.update', $creditCardInvoice->id], 'method' => 'patch']) !!}

                        @include('creditCardInvoices.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection