@extends('layouts.admin')
@section('content')
    @php
    use App\product;
    use App\productcat;
    @endphp
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách sản phẩm</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input style="padding:0px;" type="" class="form-control form-search" name="keyword"
                            value="{{ request()->input('keyword') }}" placeholder="Tìm kiếm">
                        <input style="padding:6px;" type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">

                <div class="analytic">
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'trash']) }}" class="text-primary">Vô hiệu
                        hóa<span class="text-muted">({{ $count[1] }})</span></a>
                </div>
                @if (count($listproducts) > 0)
                    <form action="{{ url('admin/product/actionproduct') }}" method='GET'>
                        <div class="form-action form-inline py-3">
                            <select class="form-control mr-1" id="" name='act'>
                                <option>Chọn</option>
                                @foreach ($list_act as $k => $act)
                                    <option value="{{ $k }}">{{ $act }}</option>
                                @endforeach
                            </select>
                            <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                        </div>
                        <table class="table table-striped table-checkall">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <input name="checkall" type="checkbox">
                                    </th>
                                    <th scope="col">STT</th>
                                    <th scope="col">Mã SP</th>
                                    <th scope="col" class="thumbnail">Ảnh sản phẩm</th>
                                    <th scope="col">Tên sản phẩm</th>
                                    <th scope="col">Màu SP</th>
                                    <th scope="col">Giá sản phẩm</th>
                                    <th scope="col">Ngày tạo </th>
                                    <th scope="col">Ngày cập nhật</th>
                                    <th scope="col">Danh mục SP</th>
                                    {{-- <th scope="col">Link liên kết</th> --}}
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    !isset($_GET['page']) ? ($t = 0) : ($t = 16 * ($_GET['page'] - 1));
                                @endphp
                                @foreach ($listproducts as $product)
                                    @php
                                        $t++;
                                    @endphp
                                    <td class="column-masp">
                                        <input type="checkbox" name="list_check[]" value={{ $product->id }}>
                                    </td>
                                    <td class="column-masp">{{ $t }}</td>
                                    <td class="column-masp">{{ $product->masp }}</td>
                                    <td class="column-masp"><img
                                            src="{{ url('') }}{{ '/' }}{{ $product->thumbnail }}"
                                            class="thumbnail" alt="Logo"></td>
                                    <td><a href="#">{{ $product->name }}</a></td>
                                    <td class="column-masp">{{ $product->color }}</td>
                                    <td class="column-masp">{{ number_format($product->price, 0, ',', '.') }}đ</td>
                                    <td class="column-masp">{{ date('d-m-Y H:i:s', strtotime($product->created_at)) }}
                                    </td>
                                    <td class="column-masp">{{ date('d-m-Y H:i:s', strtotime($product->updated_at)) }}
                                    </td>
                                    <td class="column-masp">{{ productcat::find($product->productcat_id)->catname }}
                                    </td>
                                    {{-- <td class="column-masp">{{$product->shortened_link}}</td> --}}
                                    @if (request()->status == 'trash')
                                        <td>
                                            <a href="{{ route('restore_product', $product->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Disable"><i
                                                    class="fas fa-trash-restore-alt"></i></a>
                                            <br>
                                            <a href="{{ route('delete_product', $product->id) }}"
                                                onclick="return confirm('Bạn có chắc xóa vĩnh viễn sản phẩm này không ?')"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    @else
                                        <td>
                                            <a href="{{ route('edit_product', $product->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white mb-2" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            <br>
                                            <a href="{{ route('disable_product', $product->id) }}"
                                                class="btn btn-dark btn-sm rounded-0 text-white mb-2" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Disable"><i
                                                    class="fas fa-microphone-alt-slash"></i></a>
                                            <br>
                                            <a href="{{ route('delete_product', $product->id) }}"
                                                onclick="return confirm('Bạn có chắc xóa vĩnh viễn sản phẩm này không ?')"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                style="padding:4px 10px;" data-toggle="tooltip" data-placement="top"
                                                title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    @endif
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </form>
                    {{ $listproducts->links() }}
            </div>
        @else
            <div>
                <p class="bg-white text-danger">Không tìm thấy sản phẩm nào trong hệ thống</p>
            </div>
            @endif

        </div>
    </div>
@endsection
