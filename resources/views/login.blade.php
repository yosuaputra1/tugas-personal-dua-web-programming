<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Login</title>
</head>

<body>
<div class="row justify-content-center" style="margin-top: 150px">
    <div class="col-lg-4">
        <main class="form-registration">
            <h1 class="h3 mb-3 fw-normal text-center">Login</h1>
            <form action="/login" method="POST">
                @csrf
                <div class="form-floating">
                    <input type="email" class="form-control " name="email" id="email" required
                           value="{{ old('email') }}" placeholder="name@example.com">
                    <label for="email">Email address</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control rounded-bottom" name="password" id="password" required
                           placeholder="Password">
                    <label for="password">Password</label>
                </div>
                <div class="form-floating mt-4 mb-4">
                    <div class="captcha">
                        <span>{!! captcha_img('mini') !!}</span>
                        <button type="button" class="btn btn-primary" class="reload" id="reload">
                            &#x21bb;
                        </button>
                    </div>
                </div>
                <div class="form-floating mb-4">
                    <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                    <label for="captcha">Enter Captcha</label>
                </div>
                @error('loginError')
                    <div class="alert alert-danger">
                        <p>{{ $message }}</p>
                    </div>
                @enderror
                <button id="loginBtn" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Login</button>
                @if(session()->has('nextAllowedLoginAttemptTime'))
                    <p class="text-danger">Anda melebihi batas percobaan login.
                        Silakan coba lagi dalam: <span id="seconds">30</span></p>
                    <script>
                        document.getElementById("loginBtn").disabled = true;
                        let endDate = new Date({{session()->get('nextAllowedLoginAttemptTime') * 1000}});
                        let secondsLabel = document.getElementById("seconds");
                        let timer = setInterval(updateCounter, 1000);
                        function updateCounter() {
                            let currentDate = new Date();
                            let secondsRemaining = Math.round((endDate.getTime() - currentDate.getTime()) / 1000);
                            secondsLabel.innerHTML = "" + secondsRemaining;
                            secondsRemaining--;
                            if (secondsRemaining < 0){
                                clearInterval(timer);
                                document.getElementById("loginBtn").disabled = false;
                                secondsLabel.remove();
                            }
                        }

                    </script>
                @endif
            </form>
            <small class="d-block mt-3">Doesn't have an account? <a class="text-danger" href="/register">
                    Register
                    Now!</a></small>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
</body>
<script type="text/javascript">
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });
</script>
</html>
