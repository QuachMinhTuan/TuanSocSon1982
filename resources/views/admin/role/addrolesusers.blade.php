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
                        <form action="{{ url('admin/role/addstoreroleuser') }}" 
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="nameRole" style="font-weight:bold;">Tên quyền</label>
                                <select name="nameRole" class="form-control mb-3" id="nameRole">
                                    <option value="">Chọn quyền</option>
                                    @foreach($roles as $role)
                                    <option value="{{$role->nameRole}}" {{old('nameRole')==$role->nameRole?'selected=selected' : ''}}>{{$role->nameRole}}</option>
                                    @endforeach
                                </select>
                                @error('nameRole')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <br>
                                <label for="nameUser" style="font-weight:bold;">Tên user</label>
                                <select name="nameUser" class="form-control mb-3" id="nameUser">
                                    <option value="">Chọn user</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}" {{old('nameUser')==$user->id?'selected=selected' : ''}}>{{$user->name}}</option>
                                    @endforeach
                                </select>
                                @error('nameUser')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="addroleuser" value="addroleuser">Thêm
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
                        @if (count($rolesusers) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">TÊN QUYỀN</th>
                                        <th scope="col">CHỨC NĂNG</th>
                                        <th scope="col">TÊN USER</th>
                                        <th scope="col">NGÀY TẠO</th>
                                        <th scope="col">NGÀY CẬP NHẬT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($rolesusers as $rolesuser)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td scope="row">{{ $t }}</td>
                                            <td class="r">{{$rolesuser->nameRole}}</td>
                                            <td class="">{{$rolesuser->nameRole=='admintrator'?'Quản trị viên':'Chỉ xem'}}</td>
                                            <td class="">{{$rolesuser->user_id}}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($rolesuser->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($rolesuser->updated_at)) }}</td>
                                            <td>
                                                <a href="{{ route('delete_role_user', $rolesuser->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa vĩnh viễn quyền của user này không ?')"
                                                    class="btpx-2n btn-danger btn-sm rounded-0" type="button"
                                                     data-toggle="tooltip" data-placement="top"
                                                    title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
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
