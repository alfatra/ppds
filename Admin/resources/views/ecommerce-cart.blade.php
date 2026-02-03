@extends('layouts.master')
@section('title')
    Cart
@endsection
@push('css')
    <link href="{{ URL::asset('build/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <x-breadcrumb pagetitle="Ecommerce" title="Cart" />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered mb-0 table-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 120px">Product</th>
                                    <th>Product Desc</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <img src="{{ URL::asset('build/images/product/img-1.png') }}" alt="product-img" title="product-img"
                                            class="avatar-md" />
                                    </td>
                                    <td>
                                        <h5 class="font-size-14 text-truncate"><a href="ecommerce-product-detail"
                                                class="text-reset">Full sleeve T-shirt</a></h5>
                                        <p class="mb-0">Color : <span class="fw-medium">Blue</span></p>
                                    </td>
                                    <td>
                                        $ 240
                                    </td>
                                    <td>
                                        <div style="width: 120px;" class="product-cart-touchspin">
                                            <input data-bs-toggle="touchspin" type="text" value="02">
                                        </div>
                                    </td>
                                    <td>
                                        $ 480
                                    </td>
                                    <td style="width: 90px;" class="text-center">
                                        <a href="javascript:void(0);" class="action-icon text-danger"> <i
                                                class="mdi mdi-trash-can font-size-18"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{ URL::asset('build/images/product/img-2.png') }}" alt="product-img" title="product-img"
                                            class="avatar-md" />
                                    </td>
                                    <td>
                                        <h5 class="font-size-14 text-truncate"><a href="ecommerce-product-detail"
                                                class="text-reset">Half sleeve T-shirt</a></h5>
                                        <p class="mb-0">Color : <span class="fw-medium">Red</span></p>
                                    </td>
                                    <td>
                                        $ 225
                                    </td>
                                    <td>
                                        <div style="width: 120px;" class="product-cart-touchspin">
                                            <input data-bs-toggle="touchspin" type="text" value="01">
                                        </div>
                                    </td>
                                    <td>
                                        $ 225
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);" class="action-icon text-danger"> <i
                                                class="mdi mdi-trash-can font-size-18"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{ URL::asset('build/images/product/img-3.png') }}" alt="product-img" title="product-img"
                                            class="avatar-md" />
                                    </td>
                                    <td>
                                        <h5 class="font-size-14 text-truncate"><a href="ecommerce-product-detail"
                                                class="text-reset">Hoodie (Green)</a></h5>
                                        <p class="mb-0">Color : <span class="fw-medium">Green</span></p>
                                    </td>
                                    <td>
                                        $ 275
                                    </td>
                                    <td>
                                        <div style="width: 120px;" class="product-cart-touchspin">
                                            <input data-bs-toggle="touchspin" type="text" value="02">
                                        </div>
                                    </td>
                                    <td>
                                        $ 550
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);" class="action-icon text-danger"> <i
                                                class="mdi mdi-trash-can font-size-18"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{ URL::asset('build/images/product/img-4.png') }}" alt="product-img" title="product-img"
                                            class="avatar-md" />
                                    </td>
                                    <td>
                                        <h5 class="font-size-14 text-truncate"><a href="ecommerce-product-detail"
                                                class="text-reset">Hoodie (Gray)</a></h5>
                                        <p class="mb-0">Color : <span class="fw-medium">Gray</span></p>
                                    </td>
                                    <td>
                                        $ 275
                                    </td>
                                    <td>
                                        <div style="width: 120px;" class="product-cart-touchspin">
                                            <input data-bs-toggle="touchspin" type="text" value="01">
                                        </div>
                                    </td>
                                    <td>
                                        $ 275
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);" class="action-icon text-danger"> <i
                                                class="mdi mdi-trash-can font-size-18"></i></a>
                                    </td>
                                </tr>
                                <tr class="bg-light text-end">

                                    <th scope="row" colspan="5">
                                        Sub Total :
                                    </th>

                                    <td>
                                        $ 1530
                                    </td>
                                </tr>
                                <tr class="bg-light text-end">

                                    <th scope="row" colspan="5">
                                        Discount :
                                    </th>

                                    <td>
                                        - $ 30
                                    </td>
                                </tr>
                                <tr class="bg-light text-end">

                                    <th scope="row" colspan="5">
                                        Shipping Charge :
                                    </th>

                                    <td>
                                        $ 25
                                    </td>
                                </tr>
                                <tr class="bg-light text-end">

                                    <th scope="row" colspan="5">
                                        Total :
                                    </th>

                                    <td>
                                        $ 1525
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
@endsection
@push('script')
    <!-- Bootrstrap touchspin -->
    <script src="{{ URL::asset('build/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/ecommerce-cart.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endpush
