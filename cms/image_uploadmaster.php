<?php

// id
// location
// date_create

?>
<?php if ($image_upload->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_image_uploadmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($image_upload->id->Visible) { // id ?>
		<tr id="r_id">
			<td class="col-sm-2"><?php echo $image_upload->id->FldCaption() ?></td>
			<td<?php echo $image_upload->id->CellAttributes() ?>>
<span id="el_image_upload_id">
<span<?php echo $image_upload->id->ViewAttributes() ?>>
<?php echo $image_upload->id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($image_upload->location->Visible) { // location ?>
		<tr id="r_location">
			<td class="col-sm-2"><?php echo $image_upload->location->FldCaption() ?></td>
			<td<?php echo $image_upload->location->CellAttributes() ?>>
<span id="el_image_upload_location">
<span<?php echo $image_upload->location->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($image_upload->location, $image_upload->location->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($image_upload->date_create->Visible) { // date_create ?>
		<tr id="r_date_create">
			<td class="col-sm-2"><?php echo $image_upload->date_create->FldCaption() ?></td>
			<td<?php echo $image_upload->date_create->CellAttributes() ?>>
<span id="el_image_upload_date_create">
<span<?php echo $image_upload->date_create->ViewAttributes() ?>>
<?php echo $image_upload->date_create->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
