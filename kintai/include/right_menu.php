<div id="right_menu">
	<div class="tblStatusCtn">
		<h3 class="rht_hdr">本日出勤/退勤</h3>
		<table>
			<tr>
				<td><span class="st">出勤</span><?php
					if (!$checkInTime) {
						echo '<span class="si f">-</span>';
					} else {
						echo '<span class="si t">' . $checkInTime[0]['attd_in_time'] . '</span>';
					}
					?></td>
			</tr>
			<tr>
				<td><span class="st">退勤</span><?php
					if (!$checkOutTime) {
						echo '<span class="si f">-</span>';
					} else {
						echo '<span class="si t">' . $checkOutTime[0]['attd_out_time'] . '</span>';
					}
					?></td>
			</tr>
		</table>
	</div>
</div>