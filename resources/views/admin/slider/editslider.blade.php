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
                        Cập nhật slider
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/slider/updateslider', $slider->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name_slider">Tên slider</label><br>
                                <input type="text" id="name_slider" name='name_slider' class="form-control"
                                    value="{{ $slider->name_slider }}" /><br>
                                @error('name_slider')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="file">Ảnh slider</label><br>
                                <input type="file" id="file" name='file' value="{{ old('file') }}" /><br>
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="editslider" value="editslider">Cập
                                nhật</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách ảnh slider
                    </div>
                    <div class="card-body">
                        @if (count($sliders) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
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
                                    @foreach ($sliders as $slider)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td class="column-sliceder"><img src="{{ asset($slider->image_slider) }}"
                                                    class="img-sliceder" alt="Logo"></td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($slider->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($slider->updated_at)) }}</td>
                                            @if ($slider->disabler == 'active')
                                                <td class="text-success">Đang kích hoạt</td>
                                            @else
                                                <td class="text-danger">Vô hiệu hóa</td>
                                            @endif
                                            <td>
                                                <a href="{{ route('edit_slider', $slider->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <br>
                                                <a href="{{ route('disable_slider', $slider->id) }}"
                                                    class="btn btn-dark btn-sm rounded-0 mb-2" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Disble"><i
                                                        class="fas fa-microphone-slash"></i></a>
                                                <br>
                                                <a href="{{ route('restore_slider', $slider->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 mb-2" type="button"
                                                    data-toggle="tooltip"style="padding:4px 10px;" data-placement="top" title="Restore"><i class="fas fa-trash-restore-alt"></i></a>
                                                <br>
                                                <a href="{{ route('delete_slider', $slider->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa slider này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0" type="button" style="padding:4px 10px;"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p style="color:red;">Không có ảnh slider nào trong hệ thống!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
