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
              <li class="breadcrumb-item"><a href="{{ route('subcategory.index') }}">Subcategory</a></li>
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
                <h3 class="card-title">Subcategory Form</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="POST" action="{{ route('subcategory.store') }}">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="category">Select category</label>
                    <select name="category" class="form-control @error('category') is-invalid @enderror">
                      <option selected disabled>Select category</option>
                      @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}"
                          @if (old('category') == $category['id']) {{ 'selected' }} @endif>
                          {{ $category['name'] }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="subname">Subcategory name</label>
                    <input type="text" class="form-control @error('subname') is-invalid @enderror" name="subname"
                      id="subname" placeholder="Enter subcategory name" value="{{ old('subname') }}">
                    @error('subname')
                      <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
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
