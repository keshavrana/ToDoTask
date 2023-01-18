<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do Task</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="row container d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="card px-3">
                        <div class="card-body">
                            <ul class="d-flex flex-column-reverse todo-list">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="checkbox" id="show_all" type="checkbox"> Show All
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </ul>
                            <h4 class="card-title">Awesome Todo list</h4>
                            <form action="" id="formsubmit">
                                @csrf
                                <div class="add-items d-flex">
                                    <input type="text" name="task" required autocomplete="off"
                                        class="form-control todo-list-input" placeholder="Project # To Do"> <button
                                        class="add btn btn-success font-weight-bold todo-list-add-btn"
                                        id="addbtn">Add</button>
                                </div>
                            </form>
                            <div id="msg"></div>
                            <div id="status"></div>
                            <div class="alert alert-danger print-error-msg" style="display:none">
                                <ul></ul>
                            </div>
                            <hr>
                            <div class="list-wrapper">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Action</th>
                                            <th scope="col">Task</th>
                                            <th scope="col">User Profile</th>
                                            <th scope="col">Delete</th>
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
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/js/all.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        $("#formsubmit").submit(function(e) {
            e.preventDefault();
            var formdata = $(this).serialize();
            $('#msg').html('');
            $('#status').html('');
            $.ajax({
                type: 'POST',
                url: "{{ url('/addtask') }}",
                data: formdata,
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        $(".print-error-msg").css('display', 'none');
                        $("#msg").append('<p class="text-success">' + data.msg + '</p>');
                        $.ajax({
                            type: 'GET',
                            url: "{{ url('/getalltask') }}",
                            success: function(result) {
                                var res = '';
                                $.each(result.data, function(key, value) {
                                    res +=
                                        '<tr>' +
                                        '<td><input class="checkbox" type="checkbox" id="task_check" data-id="' +
                                        value.id +
                                        '"></td>' +
                                        '<td>' + value.task + '</td>' +
                                        '<td><i class="fa fa-user" aria-hidden="true"></i></td>' +
                                        '<td><button id="deletebtn" data-id="' +
                                        value.id +
                                        '" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                                        '</tr>';
                                });
                                $('tbody').html(res);
                            }

                        });
                    } else {
                        printErrorMsg(data.error);
                    }


                },
            });
        });

        function printErrorMsg(msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $.each(msg, function(key, value) {
                $(".print-error-msg").find("ul").append('<li>' + msg + '</li>');
            });
        }

        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: "{{ url('/getalltask') }}",
                success: function(result) {
                    var res = '';
                    $.each(result.data, function(key, value) {
                        res +=
                            '<tr>' +
                            '<tr>' +
                            '<td><input class="checkbox" type="checkbox" id="task_check" data-id="' +
                            value.id +
                            '"></td>' +
                            '<td>' + value.task + '</td>' +
                            '<td><i class="fa fa-user" aria-hidden="true"></i></td>' +
                            '<td><button id="deletebtn" data-id="' + value.id +
                            '" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                            '</tr>';
                    });
                    $('tbody').html(res);
                }

            });


        });
    </script>
    <script>
        $(document).on('click', '#deletebtn', function() {
            $(".print-error-msg").css('display', 'none');
            $('#msg').html('');
            $('#status').html('');
            var result = confirm("Are u sure to delete this task ?");
            let id = $(this).attr("data-id");
            var v_token = "{{ csrf_token() }}";
            let params = {
                id: id,
                _token: v_token
            };
            if (result) {
                $.ajax({
                    type: 'POST',
                    data: params,
                    url: "{{ url('/deletetask') }}",
                    success: function(result) {
                        $("#status").append('<p class="text-success">' + result.status + '</p>');
                        $.ajax({
                            type: 'GET',
                            url: "{{ url('/getalltask') }}",
                            success: function(result) {
                                var res = '';
                                $.each(result.data, function(key, value) {
                                    res +=
                                        '<tr>' +
                                        '<tr>' +
                                        '<td><input class="checkbox" type="checkbox" id="task_check" data-id="' +
                                        value.id +
                                        '"></td>' +
                                        '<td>' + value.task + '</td>' +
                                        '<td><i class="fa fa-user" aria-hidden="true"></i></td>' +
                                        '<td><button id="deletebtn" data-id="' +
                                        value.id +
                                        '" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                                        '</tr>';
                                });
                                $('tbody').html(res);
                            }

                        });
                    }

                });
            }

        });
        $(document).on('click', '#task_check', function() {
            $(".print-error-msg").css('display', 'none');
            $('#msg').html('');
            $('#status').html('');
            let id = $(this).attr("data-id");
            var v_token = "{{ csrf_token() }}";
            let params = {
                id: id,
                _token: v_token
            };
            $.ajax({
                type: 'POST',
                data: params,
                url: "{{ url('/taskcompleted') }}",
                success: function(result) {
                    $("#status").append('<p class="text-success">' + result.status + '</p>');
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('/getalltask') }}",
                        success: function(result) {
                            var res = '';
                            $.each(result.data, function(key, value) {
                                res +=
                                    '<tr>' +
                                    '<tr>' +
                                    '<td><input class="checkbox" type="checkbox" id="task_check" data-id="' +
                                    value.id +
                                    '"></td>' +
                                    '<td>' + value.task + '</td>' +
                                    '<td><i class="fa fa-user" aria-hidden="true"></i></td>' +
                                    '<td><button id="deletebtn" data-id="' +
                                    value.id +
                                    '" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                                    '</tr>';
                            });
                            $('tbody').html(res);
                        }

                    });
                }
            });


        });

        $(document).on('click', '#show_all', function() {
            $.ajax({
                type: 'GET',
                url: "{{ url('/showalltask') }}",
                success: function(result) {
                    var res = '';
                    $.each(result.data, function(key, value) {
                        res +=
                            '<tr>' +
                            '<tr>' +
                            '<td><input class="checkbox" type="checkbox" id="task_check" data-id="' +
                            value.id +
                            '"></td>' +
                            '<td>' + value.task + '</td>' +
                            '<td><i class="fa fa-user" aria-hidden="true"></i></td>' +
                            '<td><button id="deletebtn" data-id="' +
                            value.id +
                            '" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                            '</tr>';
                    });
                    $('tbody').html(res);
                }

            });
        });
    </script>
</body>

</html>
