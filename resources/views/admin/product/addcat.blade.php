@extends('layouts.admin')
@section('content')
@php
    use App\parentlist;
@endphp
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">

                <div class="card">
                    <div class="card-header font-weight-bold">
                        THÊM Danh mục sản phẩm
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/product/storeaddcatproduct') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục</label>
                                <input class="form-control" type="text" name="catname" id="name"
                                    value="{{ old('catname') }}">
                                @error('catname')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <label for="name">Danh mục cha</label>
                                <select name="parent_list_id" class="form-control" id="parent_list_id" value={{ old('parent_list_id') }}>
                                    <option value="">Chọn danh cha</option>
                                    @foreach ($catparentlists as $catparent)
                                        <option value={{ $catparent->id }} {{ old('parent_list_id') == $catparent->id ? 'selected=selected' : '' }}>{{ $catparent->catparent }}</option>
                                    @endforeach
                                </select>
                                @error('parent_list_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="addcatproduct">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="card-header font-weight-bold">
                        Danh sách Danh mục
                    </div>
                    @if (count($catproducts) > 0)
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Danh mục sản phẩm</th>
                                        <th scope="col">Danh mục cha</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($catproducts as $catproduct)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td>{{ $catproduct->catname }}</td>
                                            <td>{{ parentlist::find($catproduct->parentlist_id)->catparent}}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($catproduct->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($catproduct->updated_at)) }}</td>
                                            @if ($catproduct->disabler != 'active')
                                                <td class="text-danger">Vô hiệu hóa</td>
                                            @else
                                                <td class="text-success">Đang kích hoạt</td>
                                            @endif
                                            <td>
                                                <a href="{{ route('edit_cat_product', $catproduct->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <br>
                                                <a href="{{ route('disablecatproduct', $catproduct->id) }}"
                                                    class="btn btn-dark btn-sm rounded-0 text-white mb-2" type="button"
                                                    style="padding:4px 7px;" data-toggle="tooltip" data-placement="top"
                                                    title="Disable"><i class="fas fa-microphone-alt-slash"></i></a>
                                                <br>
                                                <a href="{{ route('restorecatproduct', $catproduct->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                    style="padding:4px 10px;" data-toggle="tooltip" data-placement="top"
                                                    title="Restore"><i class="fas fa-trash-restore-alt"></i></a>
                                                <br>
                                                <a href="{{ route('delete_cat_product', $catproduct->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa vĩnh viễn danh mục sản phẩm này không, xóa là toàn bộ sản phẩm của danh mục này cũng xóa vĩnh viễn theo ?')"
                                                    class="btn btn-danger btn-sm rounded-0" type="button"
                                                    style="padding:4px 10px;" data-toggle="tooltip" data-placement="top"
                                                    title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="min-height:348px;">
                            <p style="color:red;">Không có danh mục sản phẩm nào trong hệ thống</p>
                        </div>

                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection
