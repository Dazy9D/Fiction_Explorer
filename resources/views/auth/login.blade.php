<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="{{ route('register') }}">Register</a>
    @if($errors->any())
        <div>{{ $errors->first() }}</div>
    @endif
</body>
</html>
