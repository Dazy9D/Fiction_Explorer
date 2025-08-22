<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input name="name" placeholder="Name" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>
        <input name="password_confirmation" type="password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
    <a href="{{ route('login') }}">Login</a>
    @if($errors->any())
        <div>{{ $errors->first() }}</div>
    @endif
</body>
</html>
