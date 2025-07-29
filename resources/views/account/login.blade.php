<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('account/css.css') }}">
    <title id="page-title">Sign In</title>
</head>

<body>
      <!-- Error and Success Messages -->
      <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger text-center">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="login-wrap">
        <div class="login-html">
            <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
            <label for="tab-1" class="tab" onclick="changeTitle('Sign In')">Đăng nhập</label>
            <input id="tab-2" type="radio" name="tab" class="sign-up">
            <label for="tab-2" class="tab" onclick="changeTitle('Sign Up')">Đăng kí</label>
            <input id="tab-3" type="radio" name="tab" class="forgot">
            <label for="tab-3" class="tab" onclick="changeTitle('Forgot Password')">Quên</label>

            <div class="login-form">
                <!-- Sign In Section -->
                <form class="sign-in-htm" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="group">
                        <label for="user" class="label mt-5">Username or Email</label>
                        <input id="user" type="text" class="input" name="account" value="{{ old('account') }}"
                            required>
                    </div>
                    <div class="group">
                        <label for="pass" class="label">Password</label>
                        <input id="pass" type="password" class="input" name="password" data-type="password"
                            required>
                    </div>
                    <div class="group">
                        <input id="check" type="checkbox" class="check">
                        <label for="check" class="text-secondary"><span class="icon me-3"></span><span
                                class="text-white">Lưu phiên đăng nhập</span></label>
                    </div>
                    <div class="group">
                        <input type="submit" class="button" value="Sign In">
                    </div>
                    <div class="hr"></div>
                    <div class="foot-lnk mb-3">
                        <label for="tab-3">Forgot Password?</label>
                    </div>
                    <div class="foot-lnk">
                        <a href="http://localhost:3000/" class="">Back to home</a>
                    </div>
                </form>

                <!-- Sign Up Section -->
                <form class="sign-up-htm" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="group">
                        <label for="signup-email" class="label mt-5">Email</label>
                        <input id="signup-email" type="email" class="input" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="group">
                        <label for="signup-pass" class="label">Password</label>
                        <input id="signup-pass" type="password" class="input" name="password" data-type="password" required>
                    </div>
                    <div class="group">
                        <label for="pass-confirm" class="label">Confirm Password</label>
                        <input id="pass-confirm" type="password" class="input" name="password_confirmation" data-type="password" required>
                    </div>
                    <div class="group">
                        <input type="submit" class="button" value="Sign Up">
                    </div>
                    <div class="hr"></div>
                    <div class="foot-lnk mb-3">
                        <label for="tab-1">Already Member?</label>
                    </div>
                    <div class="foot-lnk">
                        <a href="http://localhost:3000/" class="">Back to home</a>
                    </div>
                </form>
                

                <!-- Forgot Password Section -->
                <form class="forgot-htm" action="{{ route('password.forgot') }}" method="POST">
                    @csrf
                    <div class="group">
                        <label for="email" class="label mt-5">Email</label>
                        <input id="email" type="email" class="input" name="email" required>
                    </div>
                    <div class="group">
                        <input type="submit" class="button" value="Reset Password">
                    </div>
                    <div class="hr"></div>
                    <div class="foot-lnk mb-3">
                        <label for="tab-1">Back to Sign In</label>
                    </div>
                    <div class="foot-lnk">
                        <a href="http://localhost:3000/" class="">Back to home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function changeTitle(newTitle) {
            document.getElementById('page-title').innerText = newTitle; // Cập nhật tiêu đề
        }
    </script>
</body>

</html>
