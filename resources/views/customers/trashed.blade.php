<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-center mt-5">Deleted Customer Table</h1>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 mx-3">
                            <a href="{{ route('customers.index') }}" class="btn btn-danger">Cancel</a>
                            
                        </div>
                        <table class="table table-light">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>NAME</th>
                                    <th>GENDER</th>
                                    <th>PAYMENT</th>
                                    <th>COUNTRY</th>
                                    <th>IMAGE</th>
                                    <th>DELETED ON</th>
                                    <th>RESTORE</th>
                             
                                </tr>
                                @foreach ($deletedCustomers as $c)
                                    <tr>
                                        <td>{{ $c->id }}</td>
                                        <td>{{ $c->name }}</td>
                                        <td>{{ $c->gender }}</td>
                                        {{-- <td>{{join(',',$c->payment)}}</td> --}}
                                        <td>
                                            @foreach ($c->payment as $val)
                                                <span class="badge bg-info">{{ $val }} </span>
                                            @endforeach
                                        </td>
                                        <td>{{ $c->country }} </td>
                                        <td>
                                            <img src="{{ asset('storage/' . $c->image) }}" alt="no image" width=90
                                                height=90>
                                        </td>

                                        <td>{{ $c->deleted_at->format('d-m-Y h:i A') }}</td>

                                        <td>
                                            <a class="btn btn-success" href="{{ route('customers.restore', $c->id) }}"
                                                role="button">Restore</a>
                                        </td>
                                       
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>








</body>

</html>
