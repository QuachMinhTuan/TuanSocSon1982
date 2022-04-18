@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                        {{-- status : du lieu tu cac action ben AdminPostController sang --}}
                    </div>
                @endif
                <div class="card">

                    <div class="card-header font-weight-bold">
                        Cập nhật quảng cáo
                    </div>
                    <div class="card-body">

                        <form action="{{ url('admin/advertisement/updateadvertisement', $banner->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên quảng cáo</label><br>
                                <input type="text" id="name" name='name' class="form-control"
                                    value="{{ $banner->name }}" /><br>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="file">Ảnh quảng cáo(banner)</label><br>
                                <input type="file" id="file" name='file' value="{{ old('file') }}" /><br>
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary" name="addadvertisement"
                                value="addadvertisement">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">

                    <div class="card-header font-weight-bold">
                        Danh sách ảnh quảng cáo(banner)
                    </div>
                    <div class="card-body">
                        @if (count($banners) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">TÊN QUẢNG CÁO</th>
                                        <th scope="col">HÌNH ẢNH</th>
                                        <th scope="col">NGÀY TẠO</th>
                                        <th scope="col">NGÀY CẬP NHẬT</th>
                                        <th scope="col">TRẠNG THÁI</th>
                                        <th scope="col">TÁC VỤ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($banners as $banner)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td scope="row">{{ $banner->name }}</td>
                                            <td class="column-sliceder"><img src="{{ asset($banner->img_banner) }}"
                                                    class="img-sliceder" alt="Logo"></td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($banner->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($banner->updated_at)) }}</td>
                                            @if ($banner->disabler == 'active')
                                                <td class='text-success'>Đang kích hoạt</td>
                                            @else
                                                <td class="text-danger">Vô hiệu hóa</td>
                                            @endif
                                            <td>
                                                <a href="{{ route('edit_banner', $banner->id) }}"
                                                    class="btn btn-success btn-sm rounded-0" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"
                                                    style="margin-bottom:10px;"><i class="fa fa-edit"></i></a>
                                                <br>
                                                <a href="{{ route('disable_banner', $banner->id) }}"
                                                    class="btn btn-dark btn-sm rounded-0" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Disable"
                                                    style="margin-bottom:10px;"><i class="fas fa-microphone-slash"></i></a>
                                                <br>
                                                <a href="{{ route('delete_banner', $banner->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa quảng cáo này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0" type="button" style="padding:4px 10px;"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p style="color:red;">Không có ảnh banner nào trong hệ thống!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
