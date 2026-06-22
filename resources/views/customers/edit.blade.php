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
            <div class="col-md-6">
                <div class="card">

                    <div class="card-header">
                        <h1 class="text-center">EDIT CUSTOMER DATA</h1>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('customers.update' , $customer->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text" name="name" value="{{ $customer->name }}">
                            </div>

                            <div class="form-group mt-3">
                                <label>Gender</label><br>
                                <div class="form-check-inline">
                                    <input type="radio" class="form-check-input" id="male" name="gender"
                                        value="Male" {{ $customer->gender == 'Male' ?  'Checked': ''  }}>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="radio" class="form-check-input" id="female" name="gender"
                                        value="Female" {{ $customer->gender == 'Female' ? 'Checked' : ''}}>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>

                            </div>

                            <div class="form-group mt-3 mb-2">
                                <label>Payment</label> <br>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="cash" class="form-control"
                                        name="payment[]" value="Cash" {{ in_array('Cash' , $customer->payment) == 'Cash' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cash">Cash</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="cheque" class="form-control"
                                        name="payment[]" value="Cheque" {{ in_array('Cheque', $customer->payment) == 'Cheque' ? 'checked' : ''}}>
                                    <label class="form-check-label" for="cheque">Cheque</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="upi" class="form-control"
                                        name="payment[]" value="UPI" {{in_array('UPI',$customer->payment) == 'UPI' ? 'checked' : ''}}>
                                    <label class="form-check-label" for="upi">UPI</label>
                                </div>
                            </div>

                            <div class="form-group mt-3 mb-2">
                                <label>Country</label>
                                <select id="" class="form-control" name="country">
                                    
                                    <option value="India {{ $customer->country == 'India' ? 'selected' : ''}}">India</option>
                                    <option value="Nepal {{ $customer->country == 'Nepal' ? 'selected' : ''}}">Nepal</option>
                                    <option value="China {{ $customer->country == 'China' ? 'selected' : ''}}">China</option>
                                </select>
                            </div>

                            <div class="form-group mt-3">
                                <label for="my-input">Image</label>
                                <input class="form-control" type="file" name="image">
                                <img class="mt-3" src="{{ asset('storage/'.$customer->image) }}" alt="no image" width=80 height=80>
                            </div>

                            <div class="form-group mt-3">
                                <button class="btn btn-warning" role="button">Update</button>
                            <a class="btn btn-danger " href="{{ route('customers.index') }}" role="button">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
