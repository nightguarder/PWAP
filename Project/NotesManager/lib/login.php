<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/fb46939310.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <title>Notes Manager - Login</title>
</head>
<body class="bg-dark">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/index.html">
                    <i class="fas fa-sticky-note fa-lg" class="d-inline-block align-top" alt="logo"></i>
                    Notes Manager
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/index.html">Home</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="/lib/login.php">Login <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="/lib/notes.php">Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="/lib/vault.php">Vault</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <section class="py-5 mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Login form -->
                <div class="col-md-5 shadow-lg p-5 bg-light rounded"> 
                    <div class="row justify-content-center text-center">
                        <h3 class="mb-3">Sign In</h3>
                        <br>
                        <!-- Avatar -->
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="avatar"><i class="fa-solid fa-user fa-3x text-primary"></i></span>
                        </div>
                    </div>
                    <!-- Login Form -->
                    <div class="row justify-content-center">
                        <form action="/config/authenticate.php" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="remember" id="rememberMe" name="remember">
                                    <label class="form-check-label" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>
                                <div class="form-group mb-2">
                                    <button class="form-control btn btn-primary text-center" type="submit">
                                        Login
                                    </button>
                                </div>
                                <p class="text-center mt-4">Don't have an account?
                                    <a href="/lib/register.html">Register</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark py-4 fixed-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <small class="text-muted">© 2025 Notes Manager. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
