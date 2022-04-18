@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm Quyền
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/role/storeaddrole') }}" 
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="nameRole" style="font-weight:bold;">Tên quyền</label>
                                <select name="nameRole" class="form-control mb-3" id="nameRole">
                                    <option value="">Chọn quyền</option>
                                    <option value="admintrator" {{old('nameRole')=='admintrator'?'selected=selected' : ''}}>Quyền quản trị viên</option>
                                    <option value="adminwatch" {{old('nameRole')=='adminwatch'?'selected=selected' : ''}}>Quyền xem</option>
                                </select>
                                @error('nameRole')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="addrole" value="addrole">Thêm
                                mới</button>
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
                        Danh sách ảnh slider
                    </div>
                    <div class="card-body">
                        @if (count($roles) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">TÊN QUYỀN</th>
                                        <th scope="col">NGÀY TẠO</th>
                                        <th scope="col">NGÀY CẬP NHẬT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($roles as $role)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td class="column-sliceder">{{$role->nameRole}}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($role->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($role->updated_at)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p style="color:red;">Không có quyền của user nào trong hệ thống!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
