@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payment Form
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($paymentForm, ['route' => ['paymentForms.update', $paymentForm->id], 'method' => 'patch']) !!}

                        @include('paymentForms.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection