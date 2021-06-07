@extends('layouts.app')
@section('title','List Product')
@section('css')
    <style>
        
    </style>
@endsection
@section('content')
    <div class="container">
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h1 class="h2">Products</h1>
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
			<div class="col col-md-4">
				{{ Form::open(['route' => ['products.index'], 'method' => 'GET']) }}
					<div class="input-group mb-3">
						<input type="text" name="search" class="form-control" placeholder="Search for a product" aria-label="Search for a product" aria-describedby="button-addon2" value="{{Request::get('search') }}">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
						</div>
					</div>
				{{ Form::close() }}
			</div>
			<div class="col">
				<a class="btn btn-outline-success" href="{{route('products.create')}}">Add Product</a>
			</div>
		</div>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-header bg-white">{{ __('Product List') }}</div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        @if(Request::get('sort_by')=='id.asc')
											<a class="sort-desc" href="{{ URL::route('products.index',['sort_by'=>'id.desc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												ID&nbsp;
											</a>
										@elseif(Request::get('sort_by')=='id.desc')
											<a class="sort-asc" href="{{ URL::route('products.index',['sort_by'=>'id.asc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												ID&nbsp;
											</a>
										@else
											<a class="sort" href="{{ URL::route('products.index',['sort_by'=>'id.desc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												ID&nbsp;
											</a>
										@endif
                                    </th>
                                    <th>
                                        @if(Request::get('sort_by')=='name.asc')
											<a class="sort-desc" href="{{ URL::route('products.index',['sort_by'=>'name.desc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												Name&nbsp;
											</a>
										@elseif(Request::get('sort_by')=='name.desc')
											<a class="sort-asc" href="{{ URL::route('products.index',['sort_by'=>'name.asc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												Name&nbsp;
											</a>
										@else
											<a class="sort" href="{{ URL::route('products.index',['sort_by'=>'name.desc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												Name&nbsp;
											</a>
										@endif
                                    </th>
									<th>
                                        @if(Request::get('sort_by')=='description.asc')
											<a class="sort-desc" href="{{ URL::route('products.index',['sort_by'=>'description.desc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												Description&nbsp;
											</a>
										@elseif(Request::get('sort_by')=='description.desc')
											<a class="sort-asc" href="{{ URL::route('products.index',['sort_by'=>'description.asc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												Description&nbsp;
											</a>
										@else
											<a class="sort" href="{{ URL::route('products.index',['sort_by'=>'description.desc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												Description&nbsp;
											</a>
										@endif
                                    </th>
                                    <th>
                                        @if(Request::get('sort_by')=='status.asc')
											<a class="sort-desc" href="{{ URL::route('products.index',['sort_by'=>'status.desc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												Status&nbsp;
											</a>
										@elseif(Request::get('sort_by')=='status.desc')
											<a class="sort-asc" href="{{ URL::route('products.index',['sort_by'=>'status.asc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												Status&nbsp;
											</a>
										@else
											<a class="sort" href="{{ URL::route('products.index',['sort_by'=>'status.desc','search' =>  (isset($_GET["search"]) ? $_GET["search"]:"" )]) }}">
												Status&nbsp;
											</a>
										@endif
                                    </th>
                                    <th width="25">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
											{{ $product->id }}
                                        </td>
                                        <td>
											{{ ucwords($product->name) }}
                                        </td>
										<td>
											{{ ucwords($product->description) }}
                                        </td>
                                        <td>
											<span class="badge badge-{{(!empty($product->status) && strcasecmp($product->status,'inactive') == 0) ? 'danger' : 'success'}}">{{ ucwords($product->status) }}</span>
                                        </td>
										<td>
											<div class="dropdown">
												<button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												  	Action
												</button>
												<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
												  	<a class="dropdown-item" href="{{route('products.show',['product' => $product]) }}">&nbsp;View</a>
												  	<a class="dropdown-item" href="{{route('products.edit',['product' => $product]) }}">&nbsp;Edit</a>
													@if(!empty($product->status) && strcasecmp($product->status,'inactive') == 0)
														<a class="dropdown-item" data-toggle="modal" data-target="#statusModal" href="#" data-id="{{$product->id}}" data-product="{{$product->name}}" data-status="active">&nbsp;Set Active</a>
													@else
														<a class="dropdown-item" data-toggle="modal" data-target="#statusModal" href="#" data-id="{{$product->id}}" data-product="{{$product->name}}" data-status="inactive">&nbsp;Set Inactive</a>
													@endif
												  	<a class="dropdown-item" data-toggle="modal" data-target="#exampleModal" href="#" data-id="{{$product->id}}" data-product="{{$product->name}}">&nbsp;Delete</a>
												</div>
											</div>
                                        </td>	
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="5">
                                            No available data in table.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="card-footer bg-white">
							<div class="row justify-content-center align-items-center">
								<div class="col-12 col-md-6 col-lg-4">
									<div class="d-flex justify-content-center justify-content-md-start align-items-center">
										<p style="margin-top:10px;color:black;">Total number of results: {{$products->count()}} of {{$products->total()}}</p>
									</div>
								</div>
								<div class="col-12 col-md-6 col-lg-8">
									<div class="d-flex justify-content-center justify-content-md-end align-items-center">
										<nav aria-label="Product List Page Pagination" class="laravel-pagination">
											{{-- @if($products->hasPages()) --}}
												<ul class="pagination bottom-pagination">
													{{-- Previous Page Link --}}
													@if($products->currentPage() === 1)
														<li class="page-item disabled">
															<a class="page-link" href="#" aria-label="Previous">
																<span aria-hidden="true">Previous</span>
															</a>
														</li>
													@else
														<li class="page-item">
															<a class="page-link" href="{{ $products->appends(['search' => Request::get('search'),'sort_by' =>  (isset($_GET["sort_by"]) ? $_GET["sort_by"]:"")])->previousPageUrl() }}" rel="prev" aria-label="Previous">
																<span aria-hidden="true">Previous</span>
															</a>
														</li>
													@endif
													@if($products->currentPage() > 3)
														<li class="page-item hidden-xs">
															<a class="page-link" href="{{ $products->appends(['search' => Request::get('search'),'sort_by' =>  (isset($_GET["sort_by"]) ? $_GET["sort_by"]:"")])->url(1) }}">
																1
															</a>
														</li>
													@endif
													@if($products->currentPage() > 4)
														<li class="page-item disabled">
															<a class="page-link" href="#" aria-label="More">
																<span aria-hidden="true">...</span>
															</a>
														</li>
													@endif
													@if($products->lastPage() > 0)
														@foreach(range(1, $products->lastPage()) as $i)
															@if($i >= $products->currentPage() - 2 && $i <= $products->currentPage() + 2)
																@if ($i == $products->currentPage())
																	<li class="page-item active">
																		<a class="page-link" href="#" aria-label="Current Page Number">
																			<span aria-hidden="true">{{ $i }}</span>
																		</a>
																	</li>
																@else
																	<li class="page-item">
																		<a class="page-link" href="{{ $products->appends(['search' => Request::get('search'),'sort_by' =>  (isset($_GET["sort_by"]) ? $_GET["sort_by"]:"")])->url($i) }}">
																			{{ $i }}
																		</a>
																	</li>
																@endif
															@endif
														@endforeach
													@else
														<li class="page-item active">
															<a class="page-link" href="#">
																1
															</a>
														</li>
													@endif
													@if($products->currentPage() < $products->lastPage() - 3)
														<li class="page-item disabled">
															<a class="page-link" href="#" aria-label="More">
																<span aria-hidden="true">...</span>
															</a>
														</li>
													@endif
													@if($products->currentPage() < $products->lastPage() - 2)
														<li class="page-item hidden-xs">
															<a class="page-link" href="{{ $products->appends(['search' => Request::get('search'),'sort_by' =>  (isset($_GET["sort_by"]) ? $_GET["sort_by"]:"")])->url($products->lastPage()) }}">
																{{ $products->lastPage() }}
															</a>
														</li>
													@endif
													{{-- Next Page Link --}}
													@if ($products->hasMorePages())
														<li class="page-item">
															<a class="page-link" href="{{ $products->appends(['search' => Request::get('search'),'sort_by' =>  (isset($_GET["sort_by"]) ? $_GET["sort_by"]:"")])->nextPageUrl() }}" rel="next" aria-label="Next">
																Next
															</a>
														</li>
													@else
														<li class="page-item disabled">
															<a class="page-link" href="#" aria-label="Next">
																<span aria-hidden="true">Next</span>
															</a>
														</li>
													@endif
												</ul>
											{{-- @endif --}}
										</nav>
									</div>
								</div>
							</div>   
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
	{{-- modal --}}
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		{{ Form::open(['route' => ['products.destroy','product' => 0],'method' => 'DELETE'])}}
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Are you sure you want to delete <strong class="product-name-modal">NAME HERE</strong>?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Yes</button>
					</div>
				</div>
			</div>
		{{ Form::close() }}
	</div>
	<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
		{{ Form::open(['route' => ['products.update.status','product' => 0,'status' => 'active'],'method' => 'PUT'])}}
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="statusModalLabel">Update Product Status</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Are you sure you want to delete <strong class="product-name-modal">NAME HERE</strong> as <strong class="product-status-modal">STATUS HERE</strong>	?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Yes</button>
					</div>
				</div>
			</div>
		{{ Form::close() }}
	</div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
			$('#exampleModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget) // Button that triggered the modal
				var productId = button.data('id');
				var productName = button.data('product');
				var modal = $(this);
				modal.find('.product-name-modal').text(productName);
				modal.find('form').attr('action',"{{route('products.index')}}/" + productId);
			});
			$('#exampleModal').on('hide.bs.modal', function (event) {
				var modal = $(this);
				modal.find('.product-name-modal').text('');
				modal.find('form').attr('action',"{{route('products.index')}}/" + 0);
			});
			$('#statusModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget) // Button that triggered the modal
				var productId = button.data('id');
				var productName = button.data('product');
				var statusName = button.data('status');
				var modal = $(this);
				modal.find('.product-name-modal').text(productName);
				modal.find('.product-status-modal').text(statusName);
				modal.find('form').attr('action',"{{route('products.index')}}/" + productId + '/update/' + statusName);
			});
			$('#statusModal').on('hide.bs.modal', function (event) {
				var modal = $(this);
				modal.find('.product-name-modal').text('');
				modal.find('.product-status-modal').text('');
				modal.find('form').attr('action',"{{route('products.index')}}/" + 0 + '/update//active');
			});
		});
    </script>
@endsection
