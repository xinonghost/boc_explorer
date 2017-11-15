<!-- <!DOCTYPE html>
<html lang="ru">
	<?php //$this->render('../common/_head'); ?>
	<body>
		<header id="header">
			<div class="center-cropped" style="background: black;">
				<div class="container">
					<div style=" float:left; padding-top: 30px;"><a href="?" style="font-size: 20px; color: #fff; "><i class="material-icons" style=" color: #0886B5; ">explorer</i>MinexExplorer</a></div>
					<div class="input-group" style=" padding: 30px 0 50px 350px;margin: 0 0 0 300px; opacity: 0.8;;overflow:auto;white-space:nowrap;">
						<form action="?r=explorer/search">
							<input type="hidden" name="r" value="explorer/search" />
							<input name="search" style="width: 400px;height: 40px; opacity: 0.7; color: black;" type="text" placeholder="Search for address, transaction or block" aria-describedby="basic-addon1" />
							<div class="input-group-addon search-button"><button type="submit"><i class="material-icons">search</i></button></div>
						</form>
					</div>
				</div>
			</div>
		</header>
		<main>
			<div class="container" style="padding-left: 35px;">
				<div style="color: #0886B5; font-size: 32px; padding-top: 15px; ">Ooops</div>
			</div>
			<hr style="border-top: 3px solid #eee;box-shadow:inset 2px 4px 3px rgba(50, 50, 50, 0.75);" />

			<div class="container" >
				<div class="row">
					<div class="col-md-12">
						<h2>Not found</h2>
						<p>This transaction or block does not exist or it's still unconfirmed.</p>
					</div>
				</div>
			</div>
		</main>

		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html> -->

<!DOCTYPE html>
<html lang="ru">
	<?php $this->render('../common/_head'); ?>
<!--   <head>
  Meta
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  SEO Meta
  <meta name="description" content="">
  <meta name="keywords" content="">
  Favicon
  <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="img/favicon/apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-touch-icon-114x114.png">
  Title
  <title>404</title>
  CSS
  build:css css/all.min.css
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/magnific-popup.css">
  <link rel="stylesheet" href="css/main.css">
  endbuild
  IE8[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]
</head> -->
  <body>
    <!-- Preloader -->
    <div id="preloader">
      <div class="cssload-whirlpool"></div>
    </div>
    <!-- Header -->
    <header id="header" class="header-inner">
      <div class="container">
        <div class="row">
          <div class="col-md-4"><a href="?" class="logo"><img src="img/logo.svg" alt="alt">MinexExplorer</a></div>
          <div class="col-md-8">
            <form class="search-form" action="?r=explorer/search">
              <div class="input-group">
					     <input type="hidden" name="r" value="explorer/search" /> 
                <input name="search" id="searchInput"  type="text" placeholder="Search for address, transaction, or block" class="form-control">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default search-button"></button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </header>
    <!-- Page Title -->
    <section class="page-title-panel">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="page-title">
              <h1>Ooops</h1>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Main -->
    <main id="main">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="text-404"><img src="img/logo-big.svg" alt="alt" width="90" height="90">
              <h2>Not found</h2>
              <p>This transaction or block does not exist or it's still unconfirmed.</p>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Footer -->
    <footer id="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="copyright">MinexSystems 2017</div>
          </div>
        </div>
      </div>
    </footer>
    <!-- JS -->
    <!-- build:js js/all.min.js -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.matchHeight-min.js"></script>
    <script src="js/SmoothScroll.js"></script>
    <script src="js/jquery.magnific-popup.js"></script>
    <script src="js/common.js"></script>
    <!-- endbuild -->
  </body>
</html>