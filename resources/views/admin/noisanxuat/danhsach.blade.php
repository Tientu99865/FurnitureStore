@extends('admin/layout/index')
@section('title')
    Danh sách nơi sản xuất
@endsection
@section('content')
    <div class="content-wrapper">
        @if(session('ThongBao'))
            <div class='card card-inverse-success' id='context-menu-access'>
                <div class='card-body'>
                    <p class='card-text' style='text-align: center;'>
                        {{session('ThongBao')}}
                    </p>
                </div>
            </div>
        @endif
        <div class="row grid-margin">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" style="text-align: center;font-size: 30px;">Danh sách nơi sản xuất</h4>

                        <p>Có tất cả <b><?php $count = DB::table('manufacture')->count(); echo $count?></b> nơi sản xuất</p><br>

                        <div id="js-grid" class="jsgrid" style="position: relative; height: 500px; width: 100%;">
                            <div class="jsgrid-grid-header jsgrid-header-scrollbar">
                                <table class="jsgrid-table">
                                    <tbody><tr class="jsgrid-header-row">
                                        <th class="jsgrid-header-cell jsgrid-align-center jsgrid-header-sortable" style="width: 50px;">
                                            #
                                        </th>
                                        <th class="jsgrid-header-cell jsgrid-align-center jsgrid-header-sortable" style="width: 100px;">
                                            Tên nơi sản xuất
                                        </th>
                                        <th class="jsgrid-header-cell jsgrid-control-field jsgrid-align-center" style="width: 50px;"><a href="admin/noisanxuat/them"><input class="jsgrid-button jsgrid-mode-button jsgrid-insert-mode-button" type="button" title="Thêm nơi sản xuất"></a></th>
                                    </tr>
                                    </tbody></table>
                            </div>
                            <div class="jsgrid-grid-body" style="height: 307.625px;">
                                <table class="jsgrid-table">
                                    <tbody>
                                    <?php
                                    $stt = 0
                                    ?>
                                    @foreach($manufacture as $manu)
                                        <?php
                                        $stt+=1;
                                        ?>
                                        <tr class="jsgrid-row">
                                            <td class="jsgrid-cell jsgrid-align-center" style="width: 50px;"><?php echo $stt;?></td>
                                            <td class="jsgrid-cell jsgrid-align-center" style="width: 100px;">{{$manu->name}}</td>
                                            <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center" style="width: 50px;">
                                                <a href="admin/noisanxuat/sua/{{$manu->id}}"><input class="jsgrid-button jsgrid-edit-button" type="button"  title="Sửa"></a>
                                                <a href="admin/noisanxuat/xoa/{{$manu->id}}"><input class="jsgrid-button jsgrid-delete-button" type="button" onclick="return confirm('Bạn có muốn xoá nơi sản xuất này không?')" title="Xóa"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="jsgrid-pager-container" >
                                <ul class="pagination" style="margin-top: 50px;">
                                    {!! $manufacture->links() !!}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
