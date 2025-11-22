<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Factor Challenge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Two Factor Challenge</div>

                    <div class="card-body">
                        <p>Please confirm access to your account by entering the authentication code provided by your authenticator application.</p>

                        <form method="POST" action="{{ url('two-factor-challenge') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required autofocus autocomplete="one-time-code">
                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <p>Or, you may enter one of your emergency recovery codes.</p>
                                <label for="recovery_code" class="form-label">Recovery Code</label>
                                <input id="recovery_code" type="text" class="form-control @error('recovery_code') is-invalid @enderror" name="recovery_code" autocomplete="one-time-code">
                                @error('recovery_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary">
                                    Log in
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
