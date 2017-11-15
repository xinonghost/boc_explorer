<!DOCTYPE html>
<html lang="ru">
	<?php $this->render('../common/_head'); ?>
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
				<div style="color: #0886B5; font-size: 32px; padding-top: 15px; ">Minexcoin address</div>
				<b>Addresses are identifiers that you use to send minexcoins to another person</b>
			</div>
			<hr style="border-top: 3px solid #eee;box-shadow:inset 2px 4px 3px rgba(50, 50, 50, 0.75);" />

			<div class="container">
				<div class="row" style="margin-top: 20px;">
					<div class="col-md-2 col-sm-2" style="margin: 20px;"><img src="https://chart.googleapis.com/chart?cht=qr&chs=200&chl=MinexBankAddress"></div>
					<div class="col-md-4 col-sm-6">
						<table class="table " style="margin-top: 50px;">
							<thead >
								<tr>
									<td style="color: #0886B5; min-width: 110px; border-top: 1px solid #0886B5"><b>General info</b></td>
									<td	style="border-top: 1px solid #0886B5"></td>
								 </tr>
							</thead>
							<tbody >

								<tr>
									<td style="color: #0886B5;"><b>Address</b></td>
									<td>Minexbank address</td>
								</tr>								 
								<tr style="border-bottom: 1.5px solid #00AEEF;">
									<td style="color: #0886B5;"><b>Hash 160</b></td>
									<td></td>
								</tr>
								<tr style="border-bottom: 1.5px solid #00AEEF;">
									<td style="color: #0886B5;"><b>Creation date</b></td>
									<td></td>
								</tr> 


							</tbody>
						</table>
					</div>
					<div class="col-md-4 col-sm-6">
						<table class="table" style="margin-top: 50px; margin-left: 30px;">
							<thead>
								<tr>
									<td style="color: #0886B5; min-width: 200px; border-top: 1px solid #0886B5"><b>Transactions</b></td>
									<td	style="border-top: 1px solid #0886B5"></td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="color: #0886B5;"><b>Number of transactions</b></td>
									<td>-</td>
								</tr>								 
								<tr style="border-bottom: 1.5px solid #00AEEF;">
									<td style="color: #0886B5;"><b>Total recivied</b></td>
									<td>0 MNX</td>
								</tr>
								<tr style="border-bottom: 1.5px solid #00AEEF;">
									<td style="color: #0886B5;"><b>Total balance</b></td>
									<td>0 MNX</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<h2>Transactions</h2>
						<p>No transactions found for this address, it has probably not been used on the network yet.</p>
					</div>
				</div>
			</div>
		</main>

		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>