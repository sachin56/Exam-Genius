@extends('layouts.app')

@section('content')

<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Todo List</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  id="myForm" enctype="multipart/form-data">
                    <input type="hidden" id="hid" name="hid">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="rate">Title</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="levy">Priority</label>
                            <select name="priority" id="priority" class="select2" required data-live-search="true" data-size="5">
                                    <option value="1">High</option>
                                    <option value="2">Low</option>
                                    <option value="3">Normal</option>
                                    <option value="4">Critical</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="rate">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Enter Description"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="rate">Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                </form>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success submit" id="submit">Save changes</button>
          </div>
      </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h1 class="m-0 text-dark">Todo List</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item active">Todo</li>
            </ol>
          </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Todo List</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="datatables">
                        <thead>
                            <tr>
                                <th style="width:20%">Name</th>
                                <th style="width:20%">Description</th>
                                <th style="width:20%">Date</th>
                                <th style="width:20%">Priority</th>
                                <th style="width:20%">Action</th>
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
<script>
    $(document).ready(function(){

        //csrf token error
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //datatable show
        show_TodoList();

        $('#priority').select2({
            theme: 'bootstrap4'
        });

        // add new employee
        $(document).on("click",".addNew",function(){

            //open the model remove previous values
            empty_form();

            $("#modal").modal('show');
            $(".modal-title").html('Save Todo List');
            $("#submit").html('Save Todo List');
            $("#submit").click(function(){
                console.log('hi');
                $("#submit").css("display","none");
                var hid =$("#hid").val();
                //save emplyee
                if(hid == ""){
                    var name =$("#name").val();
                    var description =$("#description").val();
                    var priority =$("#priority").val();
                    var end_date =$("#end_date").val();

                    $.ajax({
                    'type': 'ajax',
                    'dataType': 'json',
                    'method': 'post',
                    'data' : {name:name,description:description,priority:priority,end_date:end_date},
                    'url' : '/todolist',
                    'async': false,
                    success:function(data){
                        if(data.validation_error){
                        validation_error(data.validation_error);//if has validation error call this function
                        }

                        if(data.db_error){
                        db_error(data.db_error);
                        }

                        if(data.db_success){
                            toastr.success(data.db_success);
                        setTimeout(function(){
                            $("#modal").modal('hide');
                            location.reload();
                        }, 2000);
                        }

                    },
                    error: function(jqXHR, exception) {
                        db_error(jqXHR.responseText);
                    }
                    });
                };
            });
        });

        //employee edit
        $(document).on("click", ".edit", function(){

            var id = $(this).attr('data');
            check_priority(id);
            empty_form();
            $("#hid").val(id);
            $("#modal").modal('show');
            $(".modal-title").html('Edit Todo List');
            $("#submit").html('Update Todo List');

            $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'get',
                'url': 'todolist/'+id,
                'async': false,
                success: function(data){
                    
                    $("#name").val(data.name);
                    $("#description").val(data.description);
                    $("#priority").select2("val",data.priority);
                    $("#end_date").val(data.end_date);
                }
            });
            //user button click submit data to controller
            $("#submit").click(function(){

                    if($("#hid").val() != ""){
                        var id =$("#hid").val();

                        var name = $("#name").val();
                        var description = $("#description").val();
                        var priority = $("#priority").val();
                        var end_date = $("#end_date").val();

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, Update it!'
                            }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    'type': 'ajax',
                                    'dataType': 'json',
                                    'method': 'put',
                                    'data' : {name:name,description:description,priority:priority,end_date:end_date},
                                    'url': 'todolist/'+id,
                                    'async': false,
                                    success:function(data){
                                    if(data.validation_error){
                                        validation_error(data.validation_error);//if has validation error call this function
                                        }

                                        if(data.db_error){
                                        db_error(data.db_error);
                                        }

                                        if(data.db_success){
                                        toastr.success(data.db_success);
                                        setTimeout(function(){
                                            $("#modal").modal('hide');
                                            location.reload();
                                        }, 2000);
                                        }
                                    },
                                });
                            }
                        });
                    }
                
            });
        });

        //employee delete
        $(document).on("click", ".delete", function(){
            var id = $(this).attr('data');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            'type': 'ajax',
                            'dataType': 'json',
                            'method': 'delete',
                            'url': 'todolist/'+id,
                            'async': false,
                            success: function(data){

                            if(data){
                                toastr.success('Todolist Deleted');
                                setTimeout(function(){
                                location.reload();
                                }, 2000);

                            }

                            }
                        });

                    }

            });

        });

    });

    function check_priority(id)
    {
        var value =$("#priority").val();
        $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'get',
                'url': 'todolist/'+id,
                'async': false,
                success: function(data){
                    
                    var piority=data.priority;
                    if(piority==value){
                        toastr.error('change value');
                    }
                    
                    return true;
                }
               
            });    
    }

    //Data Table show
    function show_TodoList(){

        $('#datatables').DataTable().clear();
        $('#datatables').DataTable().destroy();

        $("#datatables").DataTable({
            'processing': true,
            'serverSide': true,
            "bLengthChange": false,
            "autoWidth": false,
            'ajax': {
                        'method': 'get',
                        'url': 'todolist/create'
            },
            'columns': [
                {data: 'name'},
                {data: 'description'},
                {data: 'end_date'},
                {
                        data: null,
                        render: function(d){
                            var html = "";
                            if(d.priority=='1'){
                                html = "<span style='padding:5px' class='badge badge-warning' >High</span>";
                            }if(d.priority=='2'){
                                html = "<span style='padding:5px' class='badge badge-info'>Low</span>";
                            }if(d.priority=='3'){
                                html = "<span style='padding:5px' class='badge badge-success' >Normal</span>";
                            }if(d.priority=='4'){
                                html = "<span style='padding:5px' class='badge badge-danger' >Critical</span>";
                            }
                            return html;
                        }
                    },

                {
                    data: null,
                    render: function(d){
                        var html = "";
                        html+="<td><button class='btn btn-warning btn-sm edit' data='"+d.id+"' title='Edit'><i class='fas fa-edit' ></i></button>";
                        html+="&nbsp;<button class='btn btn-danger btn-sm delete' data='"+d.id+"'title='Delete'><i class='fas fa-trash'></i></button>";
                        return html;

                    }

                }
            ]
        });
    }
    function empty_form(){
        $("#name").val("");
        $("#description").val("");
        $("#end_date").val("");
        $("#priority").val("");

    }

    function validation_error(error){
        for(var i=0;i< error.length;i++){
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error[i],
            });
        }
    }

    function db_error(error){
        Swal.fire({
            icon: 'error',
            title: 'Database Error',
            text: error,
        });
    }

    function db_success(message){
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
        });
    }
</script>
@endsection
