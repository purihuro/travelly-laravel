<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Exflore KBB</title>
    <style>
      body { margin: 0; font-family: Arial, sans-serif; background: linear-gradient(135deg, #f4f1e8, #d8eadf); min-height: 100vh; display: grid; place-items: center; }
      .card { width: 100%; max-width: 420px; background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 18px 50px rgba(0,0,0,.12); }
      h1 { margin-top: 0; margin-bottom: 8px; }
      p { color: #666; margin-top: 0; }
      label { display: block; margin: 14px 0 6px; font-weight: 600; }
      input { width: 100%; box-sizing: border-box; padding: 12px 14px; border: 1px solid #d7d7d7; border-radius: 10px; }
      button { width: 100%; margin-top: 18px; padding: 12px 14px; border: 0; border-radius: 10px; background: #1f4b3f; color: #fff; font-weight: 700; cursor: pointer; }
      .error { color: #b42318; font-size: 14px; margin-top: 10px; }
      .hint { margin-top: 16px; font-size: 14px; color: #666; }
    </style>
  </head>
  <body>
    <div class="card">
      <h1>Admin Login</h1>
      <p>Masuk ke dashboard Exflore KBB.</p>

      <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', 'admin@travelly.com') }}" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" value="password123" required>

        <label style="display:flex; align-items:center; gap:8px; font-weight:400; margin-top:12px;">
          <input type="checkbox" name="remember" value="1" style="width:auto;"> Remember me
        </label>

        @if ($errors->any())
          <div class="error">{{ $errors->first() }}</div>
        @endif

        <button type="submit">Masuk</button>
      </form>

      <div class="hint">
        Default admin: `admin@travelly.com` / `password123`
      </div>
    </div>
  </body>
</html>
