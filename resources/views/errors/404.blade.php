<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        h2{
            color: white;
            font-size: 4rem;
        }
        p{
            font-size: 3rem;
            color: white;
            margin: 0;
        }
        .error{
            width: 100vw;
            height: 100vh;
            background: #1a202c;
            display: flex;
            justify-content: center;
        }
        .error div{
            align-self: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        body{
            margin: 0;
        }
        a{
            font-size: 3rem;
            color: white;
            text-decoration: none;
            border: 2px solid white;
            padding: 10px;
            border-radius: 15px;
            transition: .2s ease-in-out;
            margin-top: 1rem ;
        }
        a:hover{
            background: white;
            color: #1a202c;
        }
    </style>
</head>
<body>
<div class="error">
    <div>
        <h2>Oops</h2>
        <p>Page not found</p>
        <a href="{{asset('/home')}}">Return home ?</a>
    </div>
</div>
</body>
</html>
