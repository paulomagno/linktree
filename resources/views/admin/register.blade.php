<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Devs Bio - Login</title>
    <link rel="stylesheet" href="{{url('assets/css/admin.login.css')}}" />
</head>
<body>
    <div class="loginArea">
        <h1>Cadastro</h1>

        @if ($errors->any())

            <div class="error">
               @foreach ($errors->all() as $error)
                  <li> {{$error}} </li>
               @endforeach
            </div>

        @endif

        <form  method="POST">
           @csrf

           <input type="email" name="email" placeholder="Digite seu e-mail" />
           <input type="password" name="password" placeholder="Digite sua senha" />

           <input type="submit" value="Cadastrar" />

           Já tem cadastro? <a href="{{url('/admin/login')}}"> Faça Login</a>
        </form>
    </div>
</body>
</html>
