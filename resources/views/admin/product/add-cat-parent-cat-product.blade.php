@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">

                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm danh mục cha
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/product/storeaddcatparentcatproduct') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục</label>
                                <input class="form-control" type="text" name="catparent" id="name"
                                    value="{{ old('catparent') }}">
                                @error('catparent')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="addcatparent">Thêm mới</button>
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
                        Danh sách Danh mục cha
                    </div>
                    @if (count($catparents) > 0)
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
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
                                    @foreach ($catparents as $catparent)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td>{{ $catparent->catparent }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($catparent->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($catparent->updated_at)) }}</td>
                                            @if ($catparent->disabler != 'active')
                                                <td><span class="text-danger">Vô hiệu hóa</span></td>
                                            @else
                                                <td class="text-success"><span class="text-success">Đang kích hoạt</span></td>
                                            @endif
                                            <td>
                                                <a href="{{ route('edit_cat_parent_cat_product', $catparent->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <br>
                                                <a href="{{ route('disable_cat_parent_cat_product', $catparent->id) }}"
                                                    class="btn btn-dark btn-sm rounded-0 text-white mb-2" type="button"
                                                    style="padding:4px 7px;" data-toggle="tooltip" data-placement="top"
                                                    title="Disable"><i class="fas fa-microphone-alt-slash"></i></a>
                                                <br>
                                                <a href="{{ route('restore_cat_parent_cat_product', $catparent->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                    style="padding:4px 10px;" data-toggle="tooltip" data-placement="top"
                                                    title="Restore"><i class="fas fa-trash-restore-alt"></i></a>
                                                <br>
                                                <a href="{{ route('delete_cat_parent_cat_product', $catparent->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa vĩnh viễn danh mục cha này không, xóa là toàn bộ danh mục sản phẩm và sản phẩm của danh mục này cũng xóa vĩnh viễn theo ?')"
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
                            <p style="color:red;">Không có danh mục cha nào trong hệ thống</p>
                        </div>

                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection
