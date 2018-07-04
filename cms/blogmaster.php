<?php

// blog_id
// name
// description_head
// date
// date_create

?>
<?php if ($blog->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_blogmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($blog->blog_id->Visible) { // blog_id ?>
		<tr id="r_blog_id">
			<td class="col-sm-2"><?php echo $blog->blog_id->FldCaption() ?></td>
			<td<?php echo $blog->blog_id->CellAttributes() ?>>
<span id="el_blog_blog_id">
<span<?php echo $blog->blog_id->ViewAttributes() ?>>
<?php echo $blog->blog_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($blog->name->Visible) { // name ?>
		<tr id="r_name">
			<td class="col-sm-2"><?php echo $blog->name->FldCaption() ?></td>
			<td<?php echo $blog->name->CellAttributes() ?>>
<span id="el_blog_name">
<span<?php echo $blog->name->ViewAttributes() ?>>
<?php echo $blog->name->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($blog->description_head->Visible) { // description_head ?>
		<tr id="r_description_head">
			<td class="col-sm-2"><?php echo $blog->description_head->FldCaption() ?></td>
			<td<?php echo $blog->description_head->CellAttributes() ?>>
<span id="el_blog_description_head">
<span<?php echo $blog->description_head->ViewAttributes() ?>>
<?php echo $blog->description_head->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($blog->date->Visible) { // date ?>
		<tr id="r_date">
			<td class="col-sm-2"><?php echo $blog->date->FldCaption() ?></td>
			<td<?php echo $blog->date->CellAttributes() ?>>
<span id="el_blog_date">
<span<?php echo $blog->date->ViewAttributes() ?>>
<?php echo $blog->date->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($blog->date_create->Visible) { // date_create ?>
		<tr id="r_date_create">
			<td class="col-sm-2"><?php echo $blog->date_create->FldCaption() ?></td>
			<td<?php echo $blog->date_create->CellAttributes() ?>>
<span id="el_blog_date_create">
<span<?php echo $blog->date_create->ViewAttributes() ?>>
<?php echo $blog->date_create->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
