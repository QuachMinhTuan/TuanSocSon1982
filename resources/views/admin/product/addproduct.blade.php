@extends('layouts.admin')
@section('content')

    <script type="text/javascript"
        src='https://cdn.tiny.cloud/1/9h83qo2ufalvuakmoq345b6y7xvfcqhs59m5rkbenhnkv7du/tinymce/5/tinymce.min.js'
        referrerpolicy="origin">
    </script>

{{-- <script>tinymce.init({selector:'textarea'});</script> --}}
    <script>
        var editor_config = {
    path_absolute : "http://localhost/affilate_maketting/",
    selector: 'textarea',
    relative_urls: false,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table directionality",
      "emoticons template paste textpattern"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
    file_picker_callback : function(callback, value, meta) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
      if (meta.filetype == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.openUrl({
        url : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no",
        onMessage: (api, message) => {
          callback(message.content);
        }
      });
    }
  };

  tinymce.init(editor_config);















    </script>

    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm sản phẩm
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/product/storeproduct') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="masp">Mã sản phẩm</label>
                                <input class="form-control" type="text" name="masp" id="masp" value="{{ old('masp') }}">
                                @error('masp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="shortened_link">Link liên kết rút gọn</label>
                                <input class="form-control" type="text" name="shortened_link" id="shortened_link" value="{{ old('shortened_link') }}">
                                @error('shortened_link')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="file">Ảnh sản phẩm</label><br>
                                <input  type="file" name='file' id="file" value="{{ old('file') }}" /><br>
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Giá sản phẩm</label>
                                <input class="form-control" type="number" name="price" id="price"
                                    value="{{ old('price') }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="color">Màu sản phẩm</label>
                                <select name="color" class="form-control" id="color">
                                    <option value="">Chọn màu</option>
                                    @foreach ($colors as $color)
                                        <option value={{ $color->id }} {{ old('color') == $color->id ?'selected=selected' : '' }}>{{ $color->namecolor }}</option>
                                    @endforeach
                                </select>
                                @error('color')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="the_firm">Hãng sản phẩm</label>
                                <select name="the_firm" class="form-control" id="the_firm">
                                    <option value="">Chọn hãng</option>
                                    @foreach ($companys as $company)
                                        <option value={{ $company->id }} {{ old('the_firm') == $company->id ?'selected=selected' : '' }}>{{ $company->namecompany }}</option>
                                    @endforeach
                                </select>
                                @error('the_firm')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product-details">Chi tiết sản phẩm</label>
                        <textarea id="product-details" name="product-details" class="form-control" cols="30"
                            rows="5">{{ old('product-details') }}</textarea>
                        @error('product-details')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả sản phẩm</label>
                        <textarea id="description" name="description" class="form-control" cols="30"
                            rows="5">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="list">Danh mục sản phẩm</label>
                        <select name="product_id" class="form-control" id="list" value={{ old('product_id') }}>
                            <option value="">Chọn danh mục sản phẩm</option>
                            @foreach ($catproducts as $catproduct)
                                <option value={{ $catproduct->id }} {{ old('product_id') == $catproduct->id ? 'selected=selected' : '' }}>{{ $catproduct->catname }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn_addproduct">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
