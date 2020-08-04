@extends('layouts.app')
@section('title','Add Task Details')
@section('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" rel="stylesheet">

@stop
@section('content')
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Task Form</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('task_details') }}">Task Details</a></li>
              <li class="breadcrumb-item active">Add Task</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Task Details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="form1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Task Title</label>
                                <input type="text" class="form-control" id="task_title" name="task_title" placeholder="Enter task title">
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Description</label>
                                <textarea class="form-control" id="description"  name="description" maxlength="255"  placeholder="Enter description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="exampleInputEmail1">Date Time</label>
                            <div class="input-group date" id="datetime" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="date_time"  data-target="#datetime" placeholder="Enter date time"/>
                                <div class="input-group-append" data-target="#datetime" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Priority</label>
                                <select class="form-control select2" id="priority"  style="width: 100%;">
                                    <option value=" ">Select Priority</option>
                                    <option value="0">Low</option>
                                    <option value="1">Medium</option>
                                    <option value="2">High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="button" class="btn btn-primary" onclick="AddTask();">Submit</button>
                </div>
              </form>
            </div>
         </div>
        </div>
     </div>
    </section>

@stop
@section('js')
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{ asset('assets/plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
 $(function () {
    $('#datetime').datetimepicker();
});

function AddTask() {
        var task_title=$("#task_title").val();
        var description=$("#description").val();
        var datetime=$("#date_time").val();
        var priority=$("#priority").val();
        $('span.text-danger').remove();
            $.ajax({
                url:"{{route('add_task_details')}}",
                method:"POST",
                type:"json",
                data:{
                    "_token": "{{ csrf_token() }}",
                    'task_title':task_title,
                    'description':description,
                    'date_time':datetime,
                    'priority':priority,
                },
                success:function (res) {
                    if (res==0)
                    {
                        swal({
                            title: "Done",
                            text: "Task created successfully.",
                            type: "success"
                        }).then( function() {
                            window.location.href = "{{route('task_details')}}";
                        });
                    }
                    else
                    {
                        var errors = res.errors;

                        $.each( errors, function( key, value ) {
                            $('#'+key).parent().append('<span class="text-danger" style="flex: 100%;">'+value+'</span>');
                        });
                        /*Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })*/
                    }
                }
            });
        //}
    }
 </script>
@stop