@extends('layouts.app')
@section('title','Show Product')
@section('content')
    <div class="container">
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h1 class="h2">View Product</h1>
		</div>
        <div class="row">
			<div class="col">
				@if(Session::has('flash_success'))
					<div class="alert alert-success" role="alert">
						{{ Session::get('flash_success') }}
					</div>
				@endif
				@if(Session::has('flash_error'))
					<div class="alert alert-danger" role="alert">
						{{ Session::get('flash_error') }}
					</div>
				@endif
			</div>
		</div>
		<div class="row">
			<div class="col">
				{{ Form::open(['route' => ['products.store'], 'method' => 'POST','files' => true,'id' => 'productForm']) }}
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <img src="{{ asset((!empty($product->image) ? 'storage/'.$product->image : '/uploads/products/default_product_image.jpg')) }}" id="product_thumbnail" class="img-thumbnail rounded" alt="Product Image">
                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                {{ Form::label('name','Name') }}
                                {{ Form::text('name',$product->name,['class' => 'form-control'.($errors->first('name') ? ' is-invalid' : ''),'readonly' => true]) }}
                                @if($errors->first('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name')}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                {{ Form::label('description','Description') }}
                                {{ Form::textarea('description',$product->description,['class' => 'form-control'.($errors->first('description') ? ' is-invalid' : ''),'readonly' => true]) }}
                                @if($errors->first('description'))
                                    <div class="invalid-feedback">{{ $errors->first('description')}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
				{{ Form::close() }}
			</div>
		</div>
    </div>
@endsection
