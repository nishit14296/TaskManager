@extends('layouts.app')
@section('title','Task Details')
@section('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@stop
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1>Task Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Task Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <a href="{{ route('add_task') }}" class="btn btn-primary">Add Task</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Task Title</th>
                    <th>Description</th>
                    <th>Date and Time</th>
                    <th>Priority</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
             </div>
            </div>
         </div>
        </div>
     </div>
    </section>

@stop
@section('js')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script type="text/javascript">
        var t;
        $(document).ready(function () {
            var DatatableColumnSearch=function () {
                $.fn.dataTable.Api.register("column().title()",function () {
                    return $(this.header()).text().trim()
                });
                return {
                    init:function () {
                        t=$("#example2").DataTable({
                            responsive:!0,
                            autoWidth:true,
                            fixedHeader: true,
                            lengthMenu:[10,15,20,25],
                            pageLength:10,
                            language:{
                                lengthMenu:"Display_MENU_"
                            },
                            searchDelay:500,
                            processing:!0,
                            serverSide:!0,
                            searching: true,
                            order:[[0,"asc"]],
                            ajax:{
                                url:"{{route('task_detail_list')}}",
                                type:"POST",
                                data:{
                                    "_token": "{{ csrf_token() }}",
                                    columnsDef:["ID","Name"]
                                }
                            },
                            columns:[
                                {
                                    data:"Task Title"
                                },
                                {
                                    data:"Description"
                                },
                                {
                                    data:"Date and Time"
                                },
                                {
                                    data:"Priority"
                                },
                                {
                                    data:"Action",orderable:false
                                }
                            ],
                            initComplete:function () {
                                
                            }
                        })
                    }
                }
            }();
            DatatableColumnSearch.init()
        });
        function deleteTask(TaskId) {
            swal({
                title: "Are you sure?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
                }).then(function(isconfim){
                    if(isconfim){
                        $.ajax({
                        url:"{{route('deleteTask')}}",
                        method:"POST",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            'TaskId':TaskId
                        },
                        success:function (res) {
                            t.ajax.reload();
                            swal({
                            title: "Deleted!",
                            text: "Task has been deleted.",
                            type: "success"
                        })
                        }
                    });
                    }
                });
        }



    </script>


@stop