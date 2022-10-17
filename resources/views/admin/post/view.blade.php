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
              <li class="breadcrumb-item"><a href="{{ route('post.index') }}">Post</a></li>
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
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid" src="{{ asset('post-image/' . $post['image']) }}"
                    alt="{{ $post['image'] }}">
                </div>

                <h3 class="profile-username text-center">{{ ucwords($post['title']) }}</h3>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Post Date</b> <a class="float-right">
                      {{ date_format(date_create(substr($post['created_at'], 0, strlen($post['created_at']) - 9)), 'D, d M Y') }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Views</b> <a class="float-right">543</a>
                  </li>
                  <li class="list-group-item">
                    <div class="container">
                      <div class="row">
                        <div class="col text-center">
                          <a href="{{ route('post.edit', $post['id']) }}"
                            class="btn btn-block btn-outline-info"><i class="fas fa-edit pr-2"></i> Edit</a>
                        </div>
                        <div class="col text-center">
                          <a href="#" class="btn btn-block btn-outline-danger" title="delete" data-toggle="modal"
                            data-target="#ModalCenter{{ $post['id'] }}"><i class="fas fa-trash pr-2"></i> Delete</a>
                          <!-- Modal -->
                          <div class="modal fade" id="ModalCenter{{ $post['id'] }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                <div class="modal-header  bg-danger">
                                  <h5 class="modal-title" id="exampleModalLongTitle">Warning</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  Are you sure want to delete <b>{{ strtoupper(ucfirst($post['title'])) }}</b> ?
                                </div>
                                <div class="modal-footer">
                                  <form action="{{ route('post.destroy', $post['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Delete</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Article Information</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fa fa-braille mr-1"></i> Category</strong>

                <p class="text-muted">
                  {{ ucwords($post->getCategory['name']) }}
                </p>

                <hr>

                <strong><i class="fa fa-list-alt mr-1"></i> Subcategory</strong>

                <p class="text-muted">{{ ucwords($post->getSubcategory['subname']) }}</p>

                <hr>

                <strong><i class="far fa-image mr-1"></i> Main Image</strong>

                <div class="text-center">
                  <img class="profile-user-img img-fluid mt-2" src="{{ asset('post-image/' . $post['image']) }}"
                    alt="{{ $post['image'] }}">
                </div>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> List Article Image</strong>

                @foreach ($postImages as $postImage)
                  <div class="text-center">
                    <img class="profile-user-img img-fluid mt-2" src="{{ asset('post-image/' . $postImage) }}"
                      alt="{{ $postImage }}">
                  </div>
                @endforeach
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <h2 class="pl-2">Article Detail</h2>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="container-fluid">
                  <h1 class="text-center">{{ ucwords($post['title']) }}</h1>
                  <img src="{{ asset('post-image/' . $post['image']) }}" class="rounded mx-auto d-block my-3"
                    alt="{{ $post['image'] }}" width="100%" id="imageOutput">
                </div>
                <div class="container-fluid mb-4">
                  <div class="row">
                    <div class="col-4 text-muted bg-info border-right">Post on
                      {{ date_format(date_create(substr($post['updated_at'], 0, strlen($post['updated_at']) - 9)), 'D, d M Y') }}
                    </div>
                    <div class="col-8 text-left bg-dark">Author : {{ ucwords($post->getUser['name']) }}</div>
                  </div>
                </div>
                {!! $post['content'] !!}
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@push('addon-css')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet"
    href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@push('addon-script')
  <!-- jQuery -->
  <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- DataTables -->
  <script src="{{ asset('template/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('template/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('template/dist/js/demo.js') }}"></script>
  <!-- SweetAlert2 -->
  <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <!-- page script -->
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
      $("#example1").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
@endpush
