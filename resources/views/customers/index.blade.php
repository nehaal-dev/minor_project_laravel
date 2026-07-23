@if (session('error'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"> 
 <div class="alert alert-danger" > 
    {{ session('error') }}
 </div>
</div>
    
@endif

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <title>Customer Table</title>
</head>

<body>

    <div class="container mt-5">

        <div class="card shadow">

            <!-- Card Header -->
            <div class="card-header bg-white mt-3 mb-1">

                <div class="row align-items-center">

                    <!-- Left -->
                    <div class="col-md-6">
                        <h3 class="mb-0">Customer Table</h3>
                    </div>

                    <!-- Right -->
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end mt-3 mt-md-0">
                            <form action="{{ route('customers.search') }}" method="GET" style="max-width: 350px; width:100%;">
                                <div class="input-group">
                                    <input type="search" name="search" class="form-control" value="{{request('search')}}"
                                        placeholder="Search customer..." autocomplete="off">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>
                </div>

            </div>

            <!-- Card Body -->
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">

                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        Create Customer
                    </a>

                    <a href="{{ route('customers.trashed') }}" class="btn btn-warning">
                        View Deleted Customers
                    </a>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle text-center">

                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>GENDER</th>
                                <th>PAYMENT</th>
                                <th>COUNTRY</th>
                                <th>IMAGE</th>
                                <th>SHOW</th>
                                <th>EDIT</th>
                                <th>DELETE</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($customer as  $c)
                            <tr>

                                <td>{{ $c->id }}</td>

                                <td>{{ $c->name }}</td>

                                <td>{{ $c->gender }}</td>

                                <td>
                                    @foreach ($c->payment as $val)
                                        <span class="badge bg-info">
                                            {{ $val }}
                                        </span>
                                    @endforeach
                                </td>

                                <td>{{ $c->country }}</td>

                                <td>
                                    <img src="{{ asset('storage/' . $c->image) }}" alt="No Image" width="90"
                                        height="90" class="rounded">
                                </td>

                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('customers.show', $c->id) }}">
                                        SHOW
                                    </a>
                                </td>

                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('customers.edit', $c->id) }}">
                                        EDIT
                                    </a>
                                </td>

                                <td>

                                    <form action="{{ route('customers.destroy', $c->id) }}" method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">
                                            DELETE
                                        </button>

                                    </form>

                                </td>

                            </tr>
                                
                            @empty
                                <tr> 
                                   <td colspan="9" class="text-center text-danger">
                                     No Customer Found 
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
             {{ $customer->links() }}

                </div>

            </div>

        </div>

    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.alert')?.remove();
        }, 2000);
    </script>

</body>

</html>
