@extends('admin.layouts.master')
@section('content')
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Post Form</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="POST" action="{{ route('post.update', [$post['id']]) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                  <div class="form-group">
                    <label for="title">Post title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                      id="title" placeholder="Enter post title"
                      value="{{ old('title') != null ? old('title') : $post['title'] }}">
                    @error('title')
                      <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="description">Category</label>
                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                      <option selected disabled>Select category</option>
                      @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}"
                          {{ old('category') != null && old('category') == $category['id'] ? 'selected' : ($post['category_id'] == $category['id'] ? 'selected' : '') }}>
                          {{ $category['name'] }}</option>
                      @endforeach
                    </select>
                    @error('category')
                      <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="description">Subategory</label>
                    <select name="subcategory" id="subcategory"
                      class="form-control @error('subcategory') is-invalid @enderror">
                      <option value="{{ $post['sub_category_id'] }}" selected>{{ $post->getSubcategory['subname'] }}
                      </option>
                    </select>
                    @error('subcategory')
                      <span class="error invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="picture">Upload header image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input @error('picture') is-invalid @enderror"
                          name="picture" accept="image/*" id="image">
                        <label class="custom-file-label" for="picture">Old Image ({{ $post['image'] }})</label>
                      </div>
                      @error('picture')
                        <span class="error invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <img src="{{asset('post-image/' . $post['image'])}}" class="rounded mx-auto d-block my-3" alt="{{ $post['image'] }}" width="512" id="imageOutput">
                  </div>
                  <div class="form-group">
                    <label for="article">Post article</label>
                    <div class="input-group">
                      <div style="width: 100%">
                        <textarea
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                          class="textarea @error('article') is-invalid @enderror" name="article" id="summernote">
                          {!! $post['content'] !!}
                        </textarea>
                      </div>
                      @error('article')
                        <span class="error invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update Article</button>
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
  <!-- Summernote 4 -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
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
  <!-- summernote css/js -->
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

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
    // Summernote
    $('.textarea').summernote({
      placeholder: 'Write your article here',
      tabsize: 4,
      height: 300,
      maximumImageFileSize: 1024 * 1024, // 500 KB
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize', 'fontsizeunit']],
        ['color', ['color']],
        ['insert', ['picture', 'link', 'video', 'table']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ],
      callbacks: {
        onImageUploadError: function(msg) {
          alert("File terlalu besar melebihi 1 MB tidak dapat diupload")
        },
      }
    });

    $('.textarea').summernote('fontSize', 14);

    let elem = "select[name='category']";
    $(elem).on('change', (e) => {
      let catId = $(elem).val();
      if (catId) {
        $.ajax({
          url: '/ajax/subcategory/' + catId,
          type: "GET",
          dataType: 'json',
          success: (data) => {
            $("select[name='subcategory']").empty()
            $("select[name='subcategory']").append(
              "<option value='' selected disabled>Select subcategories</option>")
            $.each(data, (key, value) => {
              $("select[name='subcategory']").append("<option value='" + key + "'>" + value +
                "</option>");
            });
          }
        });
      } else {
        $("select[name='subcategory']").empty()
      }
    })

    document.querySelector('.custom-file-input').addEventListener('change', (e) => {
      let fileName = document.getElementById('image').files[0].name
      let nextSibling = e.target.nextElementSibling
      nextSibling.innerHTML = fileName
    })

    document.querySelector('#image').addEventListener('change', (e) => {
        let output = document.getElementById('imageOutput')
        output.className = 'rounded mx-auto d-block my-3'
        output.src = URL.createObjectURL(e.target.files[0])
        output.onload = () => {
            URL.revokeObjectURL(output.src)
        }
    })
  </script>
@endpush
