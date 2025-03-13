@extends('adminlte::page')

@section('title', 'Create Shopper')

@section('content_header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Create Shopper</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Create Shopper </li>
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
            <!-- left column -->
            <div class="col-md-12">
            <!-- Shopper form elements -->
            <div class="card card-primary">
                <!-- form start -->
                <form action="{{ route('store.shopper') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter first name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Create Shopper</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
@endsection
