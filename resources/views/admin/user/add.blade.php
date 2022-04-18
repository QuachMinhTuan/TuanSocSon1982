@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm người dùng
            </div>
            <div class="card-body">
                {{-- Phan 24 bai 271 --}}
                <form action="{{url('admin/user/store')}}" method="POST">
                    {{-- file xu ly them user : action store --}}
                    {{--  @csrf:bao mat form --}}
                    @csrf
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}">
                        {{-- Phan 24 bai 272 --}}
                        @error('name')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                     <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" id="email" value="{{old('email')}}">
                        @error('email')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input class="form-control" type="password" name="password" id="password">
                        @error('password')
                             <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password-confỉm">Xác nhận mật khẩu</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password-confỉm" >
                        @error('password_confirmation')
                             <small class="text-danger">{{$message}}</small>
                         @enderror
                    </div>

                    {{-- <div class="form-group">
                        <label for="">Nhóm quyền</label>
                        <select class="form-control" id="">
                            <option>Chọn quyền</option>
                            <option>Danh mục 1</option>
                            <option>Danh mục 2</option>
                            <option>Danh mục 3</option>
                            <option>Danh mục 4</option>
                        </select>
                    </div> --}}

                    <button type="submit" class="btn btn-primary" name="btn-add" value="Thêm mới">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection

