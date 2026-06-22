<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info">
                        <h1 class="text-center">CUSTOMER DETAILS</h1>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li class="">Customer Name : {{ $customer->name }} </li>
                            <li> Customer Gender : {{ $customer->gender }} </li>
                            <li> Customer Payment : {{ join(',', $customer->payment) }} </li>
                            <li> Customer Country : {{ $customer->country }} </li>
                            <li>  Customer Image :<img class="img-fluid" src="{{ asset('storage/' . $customer->image) }}"
                             alt="no image" width="90" height=90>  </li>
                             <a class="btn btn-secondary mt-3" href="{{ route('customers.index') }}" role="button">Back</a>
                             <a class="btn btn-info mt-3" href="{{ route('customers.create') }}" role="button">Add Customer</a>

                        </ul>
                        
                       

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
