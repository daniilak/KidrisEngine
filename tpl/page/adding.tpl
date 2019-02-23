<section class="section">
	<div class="row">
		<div class="col col-xs-12 col-sm-12 col-md-6 col-xl-5">
			<div class="card card-block">
				<div class="form-group">
		        	<label class="control-label" for="description">Введите ссылку В Контакте на человека, которого вы хотите верифицировать:</label>
		            <input type="text" class="form-control boxed" value="" placeholder="vk.com/" id="description">
		        </div>
		        {facs}
				<div class="form-group">
					<button type="button" class="btn btn-primary-outline pull-right send">Верифицировать</button>
				</div>
			</div>
		</div>
		<div class="col col-xs-12 col-sm-12 col-md-6 col-xl-7">
			<div class="row accounts">
				{block_accounts}
			</div>
		</div>
	</div>
</section>