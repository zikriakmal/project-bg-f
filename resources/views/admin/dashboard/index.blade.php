@extends('layout.main',[
    'pesan'=>'<h2 class=" ml-1 my-1">Haloo, <span style="color:#e37e57;">'.auth()->user()['name'].' !</span></h1>',
    'shop'=>'<button class="btn btn-primary py-1 mt-1 " data-toggle="modal" data-target="#modalCart"> <i class="feather icon-shopping-cart"></i>
                                </button>'

    ])
@section('css')

@endsection

@section('content')
    <div class="content-header row">
    </div>


    <div class="row ">
        <div class="col-sm-12">
            <div class="card overflow-hidden">
                <div class="card-content">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active font-medium-4 px-2" id="home-tab"
                                   data-toggle="tab" href="#home"
                                   aria-controls="home" role="tab" aria-selected="true"><i
                                        class="feather icon-book-open"> Daftar Menu</i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-medium-4 px-2" id="stock-tab" data-toggle="tab"
                                   href="#stock"
                                   aria-controls="profile" role="tab" aria-selected="true"><i
                                        class="feather icon-box"> Stok</i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-medium-4 px-2" id="invoice-tab" data-toggle="tab"
                                   href="#invoice"
                                   aria-controls="profile" role="tab" aria-selected="true"><i
                                        class="feather icon-dollar-sign"> Invoice List</i></a>
                            </li>
                                <button class="btn btn-success" style="margin-top: 12px;margin-bottom: 1px" data-toggle="modal" data-target="#modalCart"> <i class="feather icon-shopping-cart"></i>
                                    Bayar
                                </button>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home" aria-labelledby="home-tab"
                                 role="tabpanel">
                                @include('admin.dashboard.component.tab-content-daftarmenu')
                            </div>
                            <div class="tab-pane" id="stock" aria-labelledby="stock-tab"
                                 role="tabpanel">
                               @include('admin.dashboard.component.tab-content-stock')
                            </div>
                            <div class="tab-pane" id="invoice" aria-labelledby="invoice-tab" role="tabpanel" >
                                @include('admin.dashboard.component.tab-content-invoice')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

  @include('admin.dashboard.component.modal-cart')

@endsection
@section('js')
    <script src="{{asset('assets')}}/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{asset('assets')}}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{asset('assets')}}/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="{{asset('assets')}}/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{asset('assets')}}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{asset('assets')}}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{asset('assets')}}/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="{{asset('assets')}}/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let temporaryData = [];
            let temps;


            $('#current_add').click(function () {
                const input = $('#current_input');
                input.val(parseInt(input.val()) + 1);
            })
            $('#current_min').click(function () {
                const input = $('#current_input');
                if (parseInt(input.val()) > 0) {
                    input.val(parseInt(input.val()) - 1);
                } else {
                    alert("weleh weleh")
                }
            })
            $('#current_appl').click(function () {
                let haha = false;
                let getIndex = 0;

                temporaryData.forEach(myFunction);

                function myFunction(item, index) {
                    if (!haha === true) {
                        haha = (item.id === temps.id ? true : false)
                        getIndex = index;
                    }
                }

                if (!haha === true) {
                    temps.amount = parseInt($('#current_input').val());
                    temporaryData.push(temps);
                    console.table(temporaryData);
                    // console.log("namboh");

                } else {
                    // console.log('update');
                    temps.amount = parseInt(temporaryData[getIndex].amount) + parseInt($('#current_input').val());
                    temporaryData[getIndex] = temps;
                    console.table(temporaryData);
                }
                $('#modalAddToCart').modal('hide');
            })
            $('#modalAddToCart').on('show.bs.modal', function (e) {
                console.table(temporaryData);

                $('#current_input').val(0);
                temps = $(e.relatedTarget).data('json');
                let haha = false;
                let getIndex = 0;
                temporaryData.forEach(myFunction);

                function myFunction(item, index) {
                    if (!haha === true) {
                        haha = (item.id === temps.id ? true : false)
                        getIndex = index;
                        $('#current_input').val(0);
                    }
                }


            })
            $('#modalCart').on('show.bs.modal', function (e) {
                // console.table(temporaryData);
                let html = "";
                let total = 0;
                temporaryData.forEach(myFunction);

                function myFunction(item, index) {
                    html += `<div class="row">
                                <div class="col-md-6 col-6">
                                    <p>${item.nama}</p>\
                                </div>
                                <div class="col-md-2 col-2">
                                    <p>${item.amount}</p>
                                </div>
                                <div class="col-md-2 col-2">
                                    <p>${item.amount * parseInt(item.harga_jual)}</p>
                                </div>
                              </div>`;
                    total += item.amount * parseInt(item.harga_jual);
                }

                html += `
                        <hr>
                        <div class="row">
                                <div class="col-md-8 col-8 text-center">
                                    <p>Total</p>
                                </div>
                                <div class="col-md-2 col-2">
                                    <p>${total}</p>
                                </div>
                              </div>`;
                $('#all-data-cart').html(html);
            })
            $('#bayar_btn').click(function () {
                $.ajax({
                    type: 'POST',
                    url: "{{route('bayar')}}",
                    data: {data: JSON.stringify(temporaryData)},
                    async: true,
                    cache: false,
                    success: (data) => {
                        window.open( "{{env('APP_URL')}}"+"/print/invoice/"+data );
                        location.reload()
                    },
                    error: function (data) {
                        // console.log(data);
                    }
                });
            })


            const dt2 = $('.zero-configuration2').DataTable({
                order: [[2, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route('invoice.dataTable')}}'
                },
                columns: [
                    {data: 'nomor_invoice', name: 'nomor_invoice', orderable: true, class: 'text-center'},
                    {data: 'user.name', name: 'user.name', orderable: true, class: 'text-center'},
                    {data: 'created_at', name: "created_at", className: "text-center"},
                    {data: 'action', name: "", searchable: false, orderable: false, className: "text-center"}
                ]
            });


        });

        function fungsiPrintInvoice(nomorinvoice){
            window.open( "{{env('APP_URL')}}"+"/print/invoice/"+nomorinvoice );
        }
    </script>
@endsection