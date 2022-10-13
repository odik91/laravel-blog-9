@extends('admin.layouts.master')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $title }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('menu.index') }}">Menu</a></li>
              <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Menu Form</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="POST" action="{{ route('menu.store') }}">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="menu">Menu</label>
                    <input type="text" class="form-control @error('menu') is-invalid @enderror" name="menu"
                      id="menu" placeholder="Enter menu name" value="{{ old('menu') }}">
                    @error('menu')
                      <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="icon">Font-awesome icon</label>
                    <input type="text" class="form-control @error('icon') is-invalid @enderror" name="icon"
                      id="icon" placeholder="Enter menu icon" value="{{ old('icon') }}">
                    @error('icon')
                      <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="route">Route</label>
                    <input type="text" class="form-control @error('route') is-invalid @enderror" name="route"
                      id="route" placeholder="Enter menu route" value="{{ old('route') }}">
                    @error('route')
                      <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@push('addon-css')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@push('addon-script')
  <!-- jQuery -->
  <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- SweetAlert2 -->
  <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <!-- Toastr -->
  <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('template/dist/js/demo.js') }}"></script>

  <script>
    $(function() {
      @if ($message = Session::get('success'))
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: "{!! $message !!}",
        })
      @elseif ($message = Session::get('error'))
        Swal.fire({
          icon: 'error',
          title: 'Opps..',
          text: "{!! $message !!}",
        })
      @endif
    });
  </script>
@endpush
