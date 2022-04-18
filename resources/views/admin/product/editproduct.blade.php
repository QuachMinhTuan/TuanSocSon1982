@extends('layouts.admin')
@section('content')
    <script
type="text/javascript"
src='https://cdn.tiny.cloud/1/0bc7tqov9nb0lobsnf5ae30auv0ia7okn9o5rpyqyzqzouyv/tinymce/5/tinymce.min.js'
referrerpolicy="origin">
</script>

<script>
var editor_config = {
//cua localhost
 path_absolute: "http://localhost/affilate_maketting/"
// cua server
// path_absolute: "http://tuantuan.unitopcv.com/",
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
                Chỉnh sửa sản phẩm
            </div>
            <div class="card-body">
                <form action="{{ url('admin/product/updateproduct', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="masp">Mã sản phẩm</label>
                                <input class="form-control" type="text" disabled name="masp" id="masp"
                                    value="{{ $product->masp }}">
                                @error('masp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="shortened_link">Link liên kết rút gọn</label>
                                <input class="form-control" type="text" name="shortened_link" id="shortened_link" value="{{ $product->shortened_link}}">
                                @error('shortened_link')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="file">Ảnh sản phẩm</label><br>
                                <input type="file" name="file" id="file" /><br>
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ $product->name }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Giá sản phẩm</label>
                                <input class="form-control" type="text" name="price" id="price"
                                    value="{{ $product->price }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="color">Màu sản phẩm</label>
                                <select name="color" class="form-control" id="color">
                                    <option value="">Chọn màu</option>
                                    @foreach ($colors as $color)
                                        <option value={{ $color->id }}
                                            {{ $product->color == $color->namecolor ? 'selected=selected' : '' }}>
                                            {{ $color->namecolor }}</option>
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
                                        <option value={{ $company->id }}
                                            {{ $product->the_firm == $company->namecompany ? 'selected=selected' : '' }}>
                                            {{ $company->namecompany }}</option>
                                    @endforeach
                                </select>
                                @error('the_firm')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="product-details">Chi tiết sản phẩm</label>
                                <textarea id="product-details" name="product-details" class="form-control" cols="30"
                                    rows="5">{{$product->product_information}}</textarea>
                                @error('product-details')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả sản phẩm</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="5"
                            value="">{{ $product->description }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="list">Danh mục sản phẩm</label>
                        <select name="product_id" class="form-control" id="list">
                            <option value="">Chọn danh mục sản phẩm</option>
                            @foreach ($productcats as $productcat)
                                <option value={{ $productcat->id }}
                                    {{ $productcat->id == $product->productcat_id ? 'selected=selected' : '' }}>
                                    {{ $productcat->catname }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn_updateproduct">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
