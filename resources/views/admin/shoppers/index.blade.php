@extends('adminlte::page')

@section('title', 'Shoppers')

@section('content_header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Shoppers</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Shoppers </li>
            </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="shoppersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Shopper ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Payment Link</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shoppers as $shopper)
                                    <tr>
                                        <td>{{ $shopper->id }}</td>
                                        <td>{{ $shopper->shopper_id }}</td>
                                        <td>{{ $shopper->first_name }} {{ $shopper->last_name }}</td>
                                        <td>{{ $shopper->email }}</td>
                                        <td class="text-center">
                                            @if($shopper->payment_link)
                                            <a href="{{ $shopper->payment_link }}" target="_blank" class="btn btn-sm btn-primary">View Link</a>
        <button class="btn btn-sm btn-warning send-email" data-id="{{ $shopper->id }}" data-email="{{ $shopper->email }}" data-link="{{ $shopper->payment_link }}">Send Email</button>
                                            @else
                                            <button class="btn btn-xs btn-warning edit-payment" 
            data-id="{{ $shopper->id }}" 
            data-link="{{ $shopper->payment_link ?? '' }}">Update</button>
                                            @endif
                                        </td>    
                                        <td>
                                            <span class="badge badge-{{ $shopper->status == 'paid' ? 'success' : ($shopper->status == 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($shopper->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content-wrapper -->
<!-- Update Payment Link Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPaymentModalLabel">Update Payment Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updatePaymentForm">
                <div class="modal-body">
                    <input type="hidden" name="shopper_id" id="shopperId">
                    <div class="form-group">
                        <label for="paymentLink">Payment Link</label>
                        <input type="url" class="form-control" name="payment_link" id="paymentLink" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>            
@endsection

@section('plugins.Datatables', true)

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#shoppersTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });

            // Open modal and populate data
            $('.edit-payment').click(function () {
                let shopperId = $(this).data('id');
                let paymentLink = $(this).data('link');

                $('#shopperId').val(shopperId);
                $('#paymentLink').val(paymentLink);
                $('#editPaymentModal').modal('show');
            });

            // AJAX to update payment link
            $('#updatePaymentForm').submit(function (e) {
                e.preventDefault();
                let formData = {
                    _token: "{{ csrf_token() }}",
                    shopper_id: $('#shopperId').val(),
                    payment_link: $('#paymentLink').val()
                };

                $.ajax({
                    url: "{{ route('update.payment.link') }}",
                    method: "POST",
                    data: formData,
                    success: function (response) {
                        alert(response.message);
                        location.reload(); // Refresh page to reflect changes
                    },
                    error: function (xhr) {
                        alert("Error updating payment link. Please try again.");
                    }
                });
            });


            $('.send-email').click(function () {
                let shopperId = $(this).data('id');
                let email = $(this).data('email');
                let link = $(this).data('link');

                if (confirm(`Send payment link to ${email}?`)) {
                    $.ajax({
                        url: "{{ route('send.payment.link') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            shopper_id: shopperId,
                            email: email,
                            payment_link: link
                        },
                        success: function (response) {
                            alert(response.message);
                        },
                        error: function (xhr) {
                            alert("Error sending email. Please try again.");
                        }
                    });
                }
            });
        });

    </script>
@stop

