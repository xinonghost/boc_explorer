<header id="header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="text-centered">
					<h1>BOC Explorer</h1>
					<p>Search for transaction or block</p>
				</div>
					
				<form action="?r=explorer/search" class="search-form">
					<div class="input-group">
						<input type="hidden" name="r" value="explorer/search" />
						<input  name="search" id="searchInput" type="text" placeholder="Search for transaction or block" class="form-control">
						<div class="input-group-btn">
							<button type="submit" class="btn btn-default search-button"></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</header>