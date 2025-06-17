<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 | Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap');
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(120deg, #ffb347 0%, #1e3c72 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Montserrat', sans-serif;
            overflow: hidden;
        }
        .container {
            text-align: center;
            color: #fff;
            position: relative;
            z-index: 2;
        }
        .error-404 {
            font-size: 8rem;
            font-weight: 700;
            letter-spacing: 10px;
            margin-bottom: 0.5rem;
            position: relative;
            animation: bounce 1.5s infinite alternate;
            text-shadow: 0 8px 32px #1e3c72aa;
        }
        @keyframes bounce {
            0% { transform: translateY(0);}
            100% { transform: translateY(-30px);}
        }
        .message {
            font-size: 1.7rem;
            margin-bottom: 2rem;
            letter-spacing: 2px;
            text-shadow: 0 2px 8px #ffb34744;
        }
        .home-btn {
            padding: 0.8rem 2.2rem;
            background: #fff;
            color: #ffb347;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 8px #1e3c7244;
            transition: background 0.2s, color 0.2s;
            text-decoration: none;
        }
        .home-btn:hover {
            background: #ffb347;
            color: #fff;
        }
        /* Animated floating circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.13;
            z-index: 1;
            animation: floatCircle 8s infinite alternate;
        }
        .circle1 {
            width: 180px; height: 180px;
            background: #fff;
            top: 10%; left: 5%;
            animation-delay: 0s;
        }
        .circle2 {
            width: 120px; height: 120px;
            background: #ffb347;
            top: 70%; left: 80%;
            animation-delay: 2s;
        }
        .circle3 {
            width: 90px; height: 90px;
            background: #1e3c72;
            top: 60%; left: 10%;
            animation-delay: 4s;
        }
        .circle4 {
            width: 60px; height: 60px;
            background: #fff;
            top: 20%; left: 75%;
            animation-delay: 1s;
        }
        @keyframes floatCircle {
            0% { transform: translateY(0) scale(1);}
            100% { transform: translateY(-30px) scale(1.1);}
        }
    </style>
</head>
<body>
    <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
    <div class="circle circle4"></div>
    <div class="container">
        <div class="error-404">404</div>
        <div class="message">Oops! The page you are looking for does not exist.</div>
        <a href="{{ url('/') }}" class="home-btn">Go Home</a>
    </div>
</body>
</html>
