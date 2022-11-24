<!DOCTYPE html>

<html>

<head>

    <title></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>



    <div class="container">

        <div class="card bg-light mt-3">

            <div class="card-header">

               All products of the store

            </div>

            <div class="card-body">





                <table class="table table-bordered mt-3">

                    <tr>

                        <th colspan="3">

                            List Of Users



                        </th>

                    </tr>

                    <tr>

                        <th>ID</th>

                        <th>Name</th>

                        <th>Email</th>

                    </tr>

                    @foreach($categorys as $user)

                    <tr>

                        <td>{{ $user->id }}</td>

                        <td>{{ $user->title }}</td>

                        <td>{{ $user->description }}</td>

                    </tr>

                    @endforeach

                </table>



            </div>

        </div>

    </div>



</body>

</html>
