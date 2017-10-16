<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>what's new</strong></div>
			<div class="panel-body">
			<?php
			$sql = "
				SELECT 
					tgl, 
					jdl, 
					ket 
				FROM t_99home
				where
					kat = '0whats_new'
				order by
					`tgl` DESC, `kat` ASC, `no_jdl` ASC, `no_ket` ASC
				";
			echo $dbhelper->ExecuteHtml($sql, array("fieldcaption" => TRUE, "tablename" => array("a_purchases")));
			?>
			</div>
		</div>
	</div>
	
	<div class="col-lg-6 col-md-6 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>on progress</strong></div>
			<div class="panel-body">
			<?php
			$sql = "
				SELECT 
					tgl, 
					jdl, 
					ket 
				FROM t_99home
				where
					kat = '1on_progress'
				order by
					`tgl` DESC, `kat` ASC, `no_jdl` ASC, `no_ket` ASC
				";
			echo $dbhelper->ExecuteHtml($sql, array("fieldcaption" => TRUE, "tablename" => array("a_purchases")));
			?>
			</div>
		</div>
	</div>
</div>