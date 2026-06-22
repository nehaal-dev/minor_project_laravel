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
                        <h1 class="text-center">CREATE CUSTOMER</h1>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text" name="name">
                            </div>

                            <div class="form-group mt-3">
                                <label>Gender</label><br>
                                <div class="form-check-inline">
                                    <input type="radio" class="form-check-input" id="male" name="gender"
                                        value="Male">
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="radio" class="form-check-input" id="female" name="gender"
                                        value="Female">
                                    <label class="form-check-label" for="female">Female</label>
                                </div>

                            </div>

                            <div class="form-group mt-3 mb-2">
                                <label>Payment</label> <br>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="cash" class="form-control"
                                        name="payment[]" value="Cash">
                                    <label class="form-check-label" for="cash">Cash</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="cheque" class="form-control"
                                        name="payment[]" value="Cheque">
                                    <label class="form-check-label" for="cheque">Cheque</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="upi" class="form-control"
                                        name="payment[]" value="UPI">
                                    <label class="form-check-label" for="upi">UPI</label>
                                </div>
                            </div>

                            <div class="form-group mt-3 mb-2">
                                <label>Country</label>
                                <select id="" class="form-control" name="country">
                                    <option>Select..</option>
                                    <option value="India">India</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="China">China</option>
                                </select>
                            </div>

                            <div class="form-group mt-3">
                                <label for="my-input">Image</label>
                                <input class="form-control" type="file" name="image">
                            </div>

                            <div class="form-group mt-3">
                                <button class="btn btn-success " role="button">Save</button>
                         <a   id="" class="btn btn-danger " href="{{ route('customers.index') }}" role="button">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
