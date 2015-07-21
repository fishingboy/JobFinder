<?php include "header.php";?>
<section class="content">
	<table class="jobtable">
		<thead>
			<tr>
				<!-- <th><input type="checkbox" class="checkbox-orders" name="checkbox-orders"></th> -->
				<th>職缺列表</th>
				<th>刊登日期</th>
				<th>職稱</th>
				<th>公司名稱<button class="table-sort"><i class="fa fa-chevron-down"></i></button></th>
				<th>新資範圍<button class="table-sort"><i class="fa fa-chevron-up"></i></button></th>
				<th>所在地</th>
				<th>總人數</th>
				<th>經歷需求</th>
				<th>資本額</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<!-- <td></td> -->
				<td><button class="btn-detail toggle-detail" title="詳細內容" data-name="detail-201405AP26001420"><i class="fa fa-search"></i></button></td>
				<td>2015/07/07</td>
				<td><div class="fixword">又帥又強清潔人員</td>
				<td><div class="fixword">ROFL股份有限公司</div></td>
				<td>100,000 ~ 100,000</td>
				<td>台北市信義區</td>
				<td>5000</td>
				<td>10年</td>
				<td>10億5千萬</td>
			</tr>
			<tr id="detail-201405AP26001420" class="job-detail">
				<td colspan="9">
					<div class="detail-content">
						<table class="jobtable">
							<thead>
								<tr>
									<!-- <th><input type="checkbox" class="checkbox-orders" name="checkbox-orders"></th> -->

									<th>職稱</th>
									<th>新資範圍<button class="table-sort"><i class="fa fa-chevron-up"></i></button></th>
									<th>經歷需求</th>
									<th>亂寫</th>
									<th>亂寫</th>
									<th>亂寫</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<!-- <td></td> -->
									<td><div class="fixword">又帥又強清潔人員</td>
									<td>100,000 ~ 100,000</td>
									<td>10年</td>
									<td>亂寫</td>
									<td>亂寫</td>
									<td>亂寫</td>
								</tr>

							</tbody>
						</table>
					</div>
				</td>
			</tr>
			<?php 
				for ($x = 0; $x <= 5; $x++) {
				echo '
					<tr>
						<!-- <td></td> -->
						<td><button class="btn-detail" title="詳細內容"><i class="fa fa-search"></i></button></td>
						<td>2015/07/07</td>
						<td><div class="fixword">又帥又強清潔人員</td>
						<td><div class="fixword">ROFL股份有限公司</div></td>
						<td>100,000 ~ 100,000</td>
						<td>台北市信義區</td>
						<td>5000</td>
						<td>10年</td>
						<td>10億5千萬</td>
					</tr>
					<tr>
						<!-- <td></td> -->
						<td><button class="btn-detail" title="詳細內容"><i class="fa fa-search"></i></button></td>
						<td>2015/07/07</td>
						<td><div class="fixword">又帥又強清潔人員</td>
						<td><div class="fixword">ROFL股份有限公司</div></td>
						<td>100,000 ~ 100,000</td>
						<td>台北市信義區</td>
						<td>5000</td>
						<td>10年</td>
						<td>10億5千萬</td>
					</tr>
				';
				}
			?> 
		</tbody>
	</table>
</section>
<?php include "footer.php";?>