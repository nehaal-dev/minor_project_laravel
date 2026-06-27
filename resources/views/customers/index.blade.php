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
    <h1 class="text-center mt-5">Customer Table</h1>


    <table class="table table-light">
        <tbody>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>GENDER</th>
                <th>PAYMENT</th>
                <th>COUNTRY</th>
                <th>IMAGE</th>
                <th>SHOW</th>
                <th>EDIT</th>
                <th>SHOW DELETED</th>
                <th>DELETE</th>
            </tr>
            @foreach ($customer as $c)
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
                        <img src="{{ asset('storage/' . $c->image) }}" alt="no image" width=90 height=90>
                    </td>

                    <td>
                        <a class="btn btn-primary" href="{{ route('customers.show' , $c->id) }}" role="button">SHOW</a>
                    </td>

                    <td>
                        <a class="btn btn-info" href="{{ route('customers.edit' , $c->id) }}" role="button">EDIT</a>
                    </td>

                    <td>
                        <a class="btn btn-secondary" href="{{ route('customers.restore' , $c->id) }}" role="button">SHOW DELETED</a>
                    </td>


                    <td>
                 <form  action="{{ route('customers.destroy' , $c->id) }}" method="POST"> 
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger">DELETE</button>

                 </form>
                               
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
