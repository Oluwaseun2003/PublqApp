<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <div class="align-middle">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <form action="{{route('kd-installer.add')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input name="database_file" type="file" class="form-control">
                                    @error('database_file')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div><br><br>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Add Installer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>