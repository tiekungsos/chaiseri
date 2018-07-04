<?php include_once "userinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($image_upload_grid)) $image_upload_grid = new cimage_upload_grid();

// Page init
$image_upload_grid->Page_Init();

// Page main
$image_upload_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$image_upload_grid->Page_Render();
?>
<?php if ($image_upload->Export == "") { ?>
<script type="text/javascript">

// Form object
var fimage_uploadgrid = new ew_Form("fimage_uploadgrid", "grid");
fimage_uploadgrid.FormKeyCountName = '<?php echo $image_upload_grid->FormKeyCountName ?>';

// Validate form
fimage_uploadgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			felm = this.GetElements("x" + infix + "_location");
			elm = this.GetElements("fn_x" + infix + "_location");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $image_upload->location->FldCaption(), $image_upload->location->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_blog_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $image_upload->blog_id->FldCaption(), $image_upload->blog_id->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fimage_uploadgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "location", false)) return false;
	if (ew_ValueChanged(fobj, infix, "blog_id", false)) return false;
	return true;
}

// Form_CustomValidate event
fimage_uploadgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fimage_uploadgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($image_upload->CurrentAction == "gridadd") {
	if ($image_upload->CurrentMode == "copy") {
		$bSelectLimit = $image_upload_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$image_upload_grid->TotalRecs = $image_upload->ListRecordCount();
			$image_upload_grid->Recordset = $image_upload_grid->LoadRecordset($image_upload_grid->StartRec-1, $image_upload_grid->DisplayRecs);
		} else {
			if ($image_upload_grid->Recordset = $image_upload_grid->LoadRecordset())
				$image_upload_grid->TotalRecs = $image_upload_grid->Recordset->RecordCount();
		}
		$image_upload_grid->StartRec = 1;
		$image_upload_grid->DisplayRecs = $image_upload_grid->TotalRecs;
	} else {
		$image_upload->CurrentFilter = "0=1";
		$image_upload_grid->StartRec = 1;
		$image_upload_grid->DisplayRecs = $image_upload->GridAddRowCount;
	}
	$image_upload_grid->TotalRecs = $image_upload_grid->DisplayRecs;
	$image_upload_grid->StopRec = $image_upload_grid->DisplayRecs;
} else {
	$bSelectLimit = $image_upload_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($image_upload_grid->TotalRecs <= 0)
			$image_upload_grid->TotalRecs = $image_upload->ListRecordCount();
	} else {
		if (!$image_upload_grid->Recordset && ($image_upload_grid->Recordset = $image_upload_grid->LoadRecordset()))
			$image_upload_grid->TotalRecs = $image_upload_grid->Recordset->RecordCount();
	}
	$image_upload_grid->StartRec = 1;
	$image_upload_grid->DisplayRecs = $image_upload_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$image_upload_grid->Recordset = $image_upload_grid->LoadRecordset($image_upload_grid->StartRec-1, $image_upload_grid->DisplayRecs);

	// Set no record found message
	if ($image_upload->CurrentAction == "" && $image_upload_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$image_upload_grid->setWarningMessage(ew_DeniedMsg());
		if ($image_upload_grid->SearchWhere == "0=101")
			$image_upload_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$image_upload_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$image_upload_grid->RenderOtherOptions();
?>
<?php $image_upload_grid->ShowPageHeader(); ?>
<?php
$image_upload_grid->ShowMessage();
?>
<?php if ($image_upload_grid->TotalRecs > 0 || $image_upload->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($image_upload_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> image_upload">
<div id="fimage_uploadgrid" class="ewForm ewListForm form-inline">
<div id="gmp_image_upload" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_image_uploadgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$image_upload_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$image_upload_grid->RenderListOptions();

// Render list options (header, left)
$image_upload_grid->ListOptions->Render("header", "left");
?>
<?php if ($image_upload->location->Visible) { // location ?>
	<?php if ($image_upload->SortUrl($image_upload->location) == "") { ?>
		<th data-name="location" class="<?php echo $image_upload->location->HeaderCellClass() ?>"><div id="elh_image_upload_location" class="image_upload_location"><div class="ewTableHeaderCaption"><?php echo $image_upload->location->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="location" class="<?php echo $image_upload->location->HeaderCellClass() ?>"><div><div id="elh_image_upload_location" class="image_upload_location">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $image_upload->location->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($image_upload->location->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($image_upload->location->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($image_upload->date_create->Visible) { // date_create ?>
	<?php if ($image_upload->SortUrl($image_upload->date_create) == "") { ?>
		<th data-name="date_create" class="<?php echo $image_upload->date_create->HeaderCellClass() ?>"><div id="elh_image_upload_date_create" class="image_upload_date_create"><div class="ewTableHeaderCaption"><?php echo $image_upload->date_create->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_create" class="<?php echo $image_upload->date_create->HeaderCellClass() ?>"><div><div id="elh_image_upload_date_create" class="image_upload_date_create">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $image_upload->date_create->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($image_upload->date_create->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($image_upload->date_create->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($image_upload->blog_id->Visible) { // blog_id ?>
	<?php if ($image_upload->SortUrl($image_upload->blog_id) == "") { ?>
		<th data-name="blog_id" class="<?php echo $image_upload->blog_id->HeaderCellClass() ?>"><div id="elh_image_upload_blog_id" class="image_upload_blog_id"><div class="ewTableHeaderCaption"><?php echo $image_upload->blog_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="blog_id" class="<?php echo $image_upload->blog_id->HeaderCellClass() ?>"><div><div id="elh_image_upload_blog_id" class="image_upload_blog_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $image_upload->blog_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($image_upload->blog_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($image_upload->blog_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($image_upload->image_id->Visible) { // image_id ?>
	<?php if ($image_upload->SortUrl($image_upload->image_id) == "") { ?>
		<th data-name="image_id" class="<?php echo $image_upload->image_id->HeaderCellClass() ?>"><div id="elh_image_upload_image_id" class="image_upload_image_id"><div class="ewTableHeaderCaption"><?php echo $image_upload->image_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="image_id" class="<?php echo $image_upload->image_id->HeaderCellClass() ?>"><div><div id="elh_image_upload_image_id" class="image_upload_image_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $image_upload->image_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($image_upload->image_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($image_upload->image_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$image_upload_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$image_upload_grid->StartRec = 1;
$image_upload_grid->StopRec = $image_upload_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($image_upload_grid->FormKeyCountName) && ($image_upload->CurrentAction == "gridadd" || $image_upload->CurrentAction == "gridedit" || $image_upload->CurrentAction == "F")) {
		$image_upload_grid->KeyCount = $objForm->GetValue($image_upload_grid->FormKeyCountName);
		$image_upload_grid->StopRec = $image_upload_grid->StartRec + $image_upload_grid->KeyCount - 1;
	}
}
$image_upload_grid->RecCnt = $image_upload_grid->StartRec - 1;
if ($image_upload_grid->Recordset && !$image_upload_grid->Recordset->EOF) {
	$image_upload_grid->Recordset->MoveFirst();
	$bSelectLimit = $image_upload_grid->UseSelectLimit;
	if (!$bSelectLimit && $image_upload_grid->StartRec > 1)
		$image_upload_grid->Recordset->Move($image_upload_grid->StartRec - 1);
} elseif (!$image_upload->AllowAddDeleteRow && $image_upload_grid->StopRec == 0) {
	$image_upload_grid->StopRec = $image_upload->GridAddRowCount;
}

// Initialize aggregate
$image_upload->RowType = EW_ROWTYPE_AGGREGATEINIT;
$image_upload->ResetAttrs();
$image_upload_grid->RenderRow();
if ($image_upload->CurrentAction == "gridadd")
	$image_upload_grid->RowIndex = 0;
if ($image_upload->CurrentAction == "gridedit")
	$image_upload_grid->RowIndex = 0;
while ($image_upload_grid->RecCnt < $image_upload_grid->StopRec) {
	$image_upload_grid->RecCnt++;
	if (intval($image_upload_grid->RecCnt) >= intval($image_upload_grid->StartRec)) {
		$image_upload_grid->RowCnt++;
		if ($image_upload->CurrentAction == "gridadd" || $image_upload->CurrentAction == "gridedit" || $image_upload->CurrentAction == "F") {
			$image_upload_grid->RowIndex++;
			$objForm->Index = $image_upload_grid->RowIndex;
			if ($objForm->HasValue($image_upload_grid->FormActionName))
				$image_upload_grid->RowAction = strval($objForm->GetValue($image_upload_grid->FormActionName));
			elseif ($image_upload->CurrentAction == "gridadd")
				$image_upload_grid->RowAction = "insert";
			else
				$image_upload_grid->RowAction = "";
		}

		// Set up key count
		$image_upload_grid->KeyCount = $image_upload_grid->RowIndex;

		// Init row class and style
		$image_upload->ResetAttrs();
		$image_upload->CssClass = "";
		if ($image_upload->CurrentAction == "gridadd") {
			if ($image_upload->CurrentMode == "copy") {
				$image_upload_grid->LoadRowValues($image_upload_grid->Recordset); // Load row values
				$image_upload_grid->SetRecordKey($image_upload_grid->RowOldKey, $image_upload_grid->Recordset); // Set old record key
			} else {
				$image_upload_grid->LoadRowValues(); // Load default values
				$image_upload_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$image_upload_grid->LoadRowValues($image_upload_grid->Recordset); // Load row values
		}
		$image_upload->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($image_upload->CurrentAction == "gridadd") // Grid add
			$image_upload->RowType = EW_ROWTYPE_ADD; // Render add
		if ($image_upload->CurrentAction == "gridadd" && $image_upload->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$image_upload_grid->RestoreCurrentRowFormValues($image_upload_grid->RowIndex); // Restore form values
		if ($image_upload->CurrentAction == "gridedit") { // Grid edit
			if ($image_upload->EventCancelled) {
				$image_upload_grid->RestoreCurrentRowFormValues($image_upload_grid->RowIndex); // Restore form values
			}
			if ($image_upload_grid->RowAction == "insert")
				$image_upload->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$image_upload->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($image_upload->CurrentAction == "gridedit" && ($image_upload->RowType == EW_ROWTYPE_EDIT || $image_upload->RowType == EW_ROWTYPE_ADD) && $image_upload->EventCancelled) // Update failed
			$image_upload_grid->RestoreCurrentRowFormValues($image_upload_grid->RowIndex); // Restore form values
		if ($image_upload->RowType == EW_ROWTYPE_EDIT) // Edit row
			$image_upload_grid->EditRowCnt++;
		if ($image_upload->CurrentAction == "F") // Confirm row
			$image_upload_grid->RestoreCurrentRowFormValues($image_upload_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$image_upload->RowAttrs = array_merge($image_upload->RowAttrs, array('data-rowindex'=>$image_upload_grid->RowCnt, 'id'=>'r' . $image_upload_grid->RowCnt . '_image_upload', 'data-rowtype'=>$image_upload->RowType));

		// Render row
		$image_upload_grid->RenderRow();

		// Render list options
		$image_upload_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($image_upload_grid->RowAction <> "delete" && $image_upload_grid->RowAction <> "insertdelete" && !($image_upload_grid->RowAction == "insert" && $image_upload->CurrentAction == "F" && $image_upload_grid->EmptyRow())) {
?>
	<tr<?php echo $image_upload->RowAttributes() ?>>
<?php

// Render list options (body, left)
$image_upload_grid->ListOptions->Render("body", "left", $image_upload_grid->RowCnt);
?>
	<?php if ($image_upload->location->Visible) { // location ?>
		<td data-name="location"<?php echo $image_upload->location->CellAttributes() ?>>
<?php if ($image_upload_grid->RowAction == "insert") { // Add record ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_location" class="form-group image_upload_location">
<div id="fd_x<?php echo $image_upload_grid->RowIndex ?>_location">
<span title="<?php echo $image_upload->location->FldTitle() ? $image_upload->location->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($image_upload->location->ReadOnly || $image_upload->location->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="image_upload" data-field="x_location" name="x<?php echo $image_upload_grid->RowIndex ?>_location" id="x<?php echo $image_upload_grid->RowIndex ?>_location"<?php echo $image_upload->location->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fn_x<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo $image_upload->location->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fa_x<?php echo $image_upload_grid->RowIndex ?>_location" value="0">
<input type="hidden" name="fs_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fs_x<?php echo $image_upload_grid->RowIndex ?>_location" value="255">
<input type="hidden" name="fx_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fx_x<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo $image_upload->location->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fm_x<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo $image_upload->location->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $image_upload_grid->RowIndex ?>_location" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="image_upload" data-field="x_location" name="o<?php echo $image_upload_grid->RowIndex ?>_location" id="o<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo ew_HtmlEncode($image_upload->location->OldValue) ?>">
<?php } elseif ($image_upload->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_location" class="image_upload_location">
<span<?php echo $image_upload->location->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($image_upload->location, $image_upload->location->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_location" class="form-group image_upload_location">
<div id="fd_x<?php echo $image_upload_grid->RowIndex ?>_location">
<span title="<?php echo $image_upload->location->FldTitle() ? $image_upload->location->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($image_upload->location->ReadOnly || $image_upload->location->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="image_upload" data-field="x_location" name="x<?php echo $image_upload_grid->RowIndex ?>_location" id="x<?php echo $image_upload_grid->RowIndex ?>_location"<?php echo $image_upload->location->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fn_x<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo $image_upload->location->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $image_upload_grid->RowIndex ?>_location"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fa_x<?php echo $image_upload_grid->RowIndex ?>_location" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fa_x<?php echo $image_upload_grid->RowIndex ?>_location" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fs_x<?php echo $image_upload_grid->RowIndex ?>_location" value="255">
<input type="hidden" name="fx_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fx_x<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo $image_upload->location->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fm_x<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo $image_upload->location->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $image_upload_grid->RowIndex ?>_location" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($image_upload->date_create->Visible) { // date_create ?>
		<td data-name="date_create"<?php echo $image_upload->date_create->CellAttributes() ?>>
<?php if ($image_upload->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="image_upload" data-field="x_date_create" name="o<?php echo $image_upload_grid->RowIndex ?>_date_create" id="o<?php echo $image_upload_grid->RowIndex ?>_date_create" value="<?php echo ew_HtmlEncode($image_upload->date_create->OldValue) ?>">
<?php } ?>
<?php if ($image_upload->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($image_upload->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_date_create" class="image_upload_date_create">
<span<?php echo $image_upload->date_create->ViewAttributes() ?>>
<?php echo $image_upload->date_create->ListViewValue() ?></span>
</span>
<?php if ($image_upload->CurrentAction <> "F") { ?>
<input type="hidden" data-table="image_upload" data-field="x_date_create" name="x<?php echo $image_upload_grid->RowIndex ?>_date_create" id="x<?php echo $image_upload_grid->RowIndex ?>_date_create" value="<?php echo ew_HtmlEncode($image_upload->date_create->FormValue) ?>">
<input type="hidden" data-table="image_upload" data-field="x_date_create" name="o<?php echo $image_upload_grid->RowIndex ?>_date_create" id="o<?php echo $image_upload_grid->RowIndex ?>_date_create" value="<?php echo ew_HtmlEncode($image_upload->date_create->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="image_upload" data-field="x_date_create" name="fimage_uploadgrid$x<?php echo $image_upload_grid->RowIndex ?>_date_create" id="fimage_uploadgrid$x<?php echo $image_upload_grid->RowIndex ?>_date_create" value="<?php echo ew_HtmlEncode($image_upload->date_create->FormValue) ?>">
<input type="hidden" data-table="image_upload" data-field="x_date_create" name="fimage_uploadgrid$o<?php echo $image_upload_grid->RowIndex ?>_date_create" id="fimage_uploadgrid$o<?php echo $image_upload_grid->RowIndex ?>_date_create" value="<?php echo ew_HtmlEncode($image_upload->date_create->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($image_upload->blog_id->Visible) { // blog_id ?>
		<td data-name="blog_id"<?php echo $image_upload->blog_id->CellAttributes() ?>>
<?php if ($image_upload->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($image_upload->blog_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_blog_id" class="form-group image_upload_blog_id">
<span<?php echo $image_upload->blog_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $image_upload->blog_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" name="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_blog_id" class="form-group image_upload_blog_id">
<input type="text" data-table="image_upload" data-field="x_blog_id" name="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" size="30" placeholder="<?php echo ew_HtmlEncode($image_upload->blog_id->getPlaceHolder()) ?>" value="<?php echo $image_upload->blog_id->EditValue ?>"<?php echo $image_upload->blog_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="image_upload" data-field="x_blog_id" name="o<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="o<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->OldValue) ?>">
<?php } ?>
<?php if ($image_upload->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($image_upload->blog_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_blog_id" class="form-group image_upload_blog_id">
<span<?php echo $image_upload->blog_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $image_upload->blog_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" name="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_blog_id" class="form-group image_upload_blog_id">
<input type="text" data-table="image_upload" data-field="x_blog_id" name="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" size="30" placeholder="<?php echo ew_HtmlEncode($image_upload->blog_id->getPlaceHolder()) ?>" value="<?php echo $image_upload->blog_id->EditValue ?>"<?php echo $image_upload->blog_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($image_upload->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_blog_id" class="image_upload_blog_id">
<span<?php echo $image_upload->blog_id->ViewAttributes() ?>>
<?php echo $image_upload->blog_id->ListViewValue() ?></span>
</span>
<?php if ($image_upload->CurrentAction <> "F") { ?>
<input type="hidden" data-table="image_upload" data-field="x_blog_id" name="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->FormValue) ?>">
<input type="hidden" data-table="image_upload" data-field="x_blog_id" name="o<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="o<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="image_upload" data-field="x_blog_id" name="fimage_uploadgrid$x<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="fimage_uploadgrid$x<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->FormValue) ?>">
<input type="hidden" data-table="image_upload" data-field="x_blog_id" name="fimage_uploadgrid$o<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="fimage_uploadgrid$o<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($image_upload->image_id->Visible) { // image_id ?>
		<td data-name="image_id"<?php echo $image_upload->image_id->CellAttributes() ?>>
<?php if ($image_upload->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="image_upload" data-field="x_image_id" name="o<?php echo $image_upload_grid->RowIndex ?>_image_id" id="o<?php echo $image_upload_grid->RowIndex ?>_image_id" value="<?php echo ew_HtmlEncode($image_upload->image_id->OldValue) ?>">
<?php } ?>
<?php if ($image_upload->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_image_id" class="form-group image_upload_image_id">
<span<?php echo $image_upload->image_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $image_upload->image_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="image_upload" data-field="x_image_id" name="x<?php echo $image_upload_grid->RowIndex ?>_image_id" id="x<?php echo $image_upload_grid->RowIndex ?>_image_id" value="<?php echo ew_HtmlEncode($image_upload->image_id->CurrentValue) ?>">
<?php } ?>
<?php if ($image_upload->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $image_upload_grid->RowCnt ?>_image_upload_image_id" class="image_upload_image_id">
<span<?php echo $image_upload->image_id->ViewAttributes() ?>>
<?php echo $image_upload->image_id->ListViewValue() ?></span>
</span>
<?php if ($image_upload->CurrentAction <> "F") { ?>
<input type="hidden" data-table="image_upload" data-field="x_image_id" name="x<?php echo $image_upload_grid->RowIndex ?>_image_id" id="x<?php echo $image_upload_grid->RowIndex ?>_image_id" value="<?php echo ew_HtmlEncode($image_upload->image_id->FormValue) ?>">
<input type="hidden" data-table="image_upload" data-field="x_image_id" name="o<?php echo $image_upload_grid->RowIndex ?>_image_id" id="o<?php echo $image_upload_grid->RowIndex ?>_image_id" value="<?php echo ew_HtmlEncode($image_upload->image_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="image_upload" data-field="x_image_id" name="fimage_uploadgrid$x<?php echo $image_upload_grid->RowIndex ?>_image_id" id="fimage_uploadgrid$x<?php echo $image_upload_grid->RowIndex ?>_image_id" value="<?php echo ew_HtmlEncode($image_upload->image_id->FormValue) ?>">
<input type="hidden" data-table="image_upload" data-field="x_image_id" name="fimage_uploadgrid$o<?php echo $image_upload_grid->RowIndex ?>_image_id" id="fimage_uploadgrid$o<?php echo $image_upload_grid->RowIndex ?>_image_id" value="<?php echo ew_HtmlEncode($image_upload->image_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$image_upload_grid->ListOptions->Render("body", "right", $image_upload_grid->RowCnt);
?>
	</tr>
<?php if ($image_upload->RowType == EW_ROWTYPE_ADD || $image_upload->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fimage_uploadgrid.UpdateOpts(<?php echo $image_upload_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($image_upload->CurrentAction <> "gridadd" || $image_upload->CurrentMode == "copy")
		if (!$image_upload_grid->Recordset->EOF) $image_upload_grid->Recordset->MoveNext();
}
?>
<?php
	if ($image_upload->CurrentMode == "add" || $image_upload->CurrentMode == "copy" || $image_upload->CurrentMode == "edit") {
		$image_upload_grid->RowIndex = '$rowindex$';
		$image_upload_grid->LoadRowValues();

		// Set row properties
		$image_upload->ResetAttrs();
		$image_upload->RowAttrs = array_merge($image_upload->RowAttrs, array('data-rowindex'=>$image_upload_grid->RowIndex, 'id'=>'r0_image_upload', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($image_upload->RowAttrs["class"], "ewTemplate");
		$image_upload->RowType = EW_ROWTYPE_ADD;

		// Render row
		$image_upload_grid->RenderRow();

		// Render list options
		$image_upload_grid->RenderListOptions();
		$image_upload_grid->StartRowCnt = 0;
?>
	<tr<?php echo $image_upload->RowAttributes() ?>>
<?php

// Render list options (body, left)
$image_upload_grid->ListOptions->Render("body", "left", $image_upload_grid->RowIndex);
?>
	<?php if ($image_upload->location->Visible) { // location ?>
		<td data-name="location">
<span id="el$rowindex$_image_upload_location" class="form-group image_upload_location">
<div id="fd_x<?php echo $image_upload_grid->RowIndex ?>_location">
<span title="<?php echo $image_upload->location->FldTitle() ? $image_upload->location->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($image_upload->location->ReadOnly || $image_upload->location->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="image_upload" data-field="x_location" name="x<?php echo $image_upload_grid->RowIndex ?>_location" id="x<?php echo $image_upload_grid->RowIndex ?>_location"<?php echo $image_upload->location->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fn_x<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo $image_upload->location->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fa_x<?php echo $image_upload_grid->RowIndex ?>_location" value="0">
<input type="hidden" name="fs_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fs_x<?php echo $image_upload_grid->RowIndex ?>_location" value="255">
<input type="hidden" name="fx_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fx_x<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo $image_upload->location->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $image_upload_grid->RowIndex ?>_location" id= "fm_x<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo $image_upload->location->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $image_upload_grid->RowIndex ?>_location" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="image_upload" data-field="x_location" name="o<?php echo $image_upload_grid->RowIndex ?>_location" id="o<?php echo $image_upload_grid->RowIndex ?>_location" value="<?php echo ew_HtmlEncode($image_upload->location->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($image_upload->date_create->Visible) { // date_create ?>
		<td data-name="date_create">
<?php if ($image_upload->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_image_upload_date_create" class="form-group image_upload_date_create">
<span<?php echo $image_upload->date_create->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $image_upload->date_create->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="image_upload" data-field="x_date_create" name="x<?php echo $image_upload_grid->RowIndex ?>_date_create" id="x<?php echo $image_upload_grid->RowIndex ?>_date_create" value="<?php echo ew_HtmlEncode($image_upload->date_create->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="image_upload" data-field="x_date_create" name="o<?php echo $image_upload_grid->RowIndex ?>_date_create" id="o<?php echo $image_upload_grid->RowIndex ?>_date_create" value="<?php echo ew_HtmlEncode($image_upload->date_create->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($image_upload->blog_id->Visible) { // blog_id ?>
		<td data-name="blog_id">
<?php if ($image_upload->CurrentAction <> "F") { ?>
<?php if ($image_upload->blog_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_image_upload_blog_id" class="form-group image_upload_blog_id">
<span<?php echo $image_upload->blog_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $image_upload->blog_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" name="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_image_upload_blog_id" class="form-group image_upload_blog_id">
<input type="text" data-table="image_upload" data-field="x_blog_id" name="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" size="30" placeholder="<?php echo ew_HtmlEncode($image_upload->blog_id->getPlaceHolder()) ?>" value="<?php echo $image_upload->blog_id->EditValue ?>"<?php echo $image_upload->blog_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_image_upload_blog_id" class="form-group image_upload_blog_id">
<span<?php echo $image_upload->blog_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $image_upload->blog_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="image_upload" data-field="x_blog_id" name="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="image_upload" data-field="x_blog_id" name="o<?php echo $image_upload_grid->RowIndex ?>_blog_id" id="o<?php echo $image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($image_upload->blog_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($image_upload->image_id->Visible) { // image_id ?>
		<td data-name="image_id">
<?php if ($image_upload->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_image_upload_image_id" class="form-group image_upload_image_id">
<span<?php echo $image_upload->image_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $image_upload->image_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="image_upload" data-field="x_image_id" name="x<?php echo $image_upload_grid->RowIndex ?>_image_id" id="x<?php echo $image_upload_grid->RowIndex ?>_image_id" value="<?php echo ew_HtmlEncode($image_upload->image_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="image_upload" data-field="x_image_id" name="o<?php echo $image_upload_grid->RowIndex ?>_image_id" id="o<?php echo $image_upload_grid->RowIndex ?>_image_id" value="<?php echo ew_HtmlEncode($image_upload->image_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$image_upload_grid->ListOptions->Render("body", "right", $image_upload_grid->RowIndex);
?>
<script type="text/javascript">
fimage_uploadgrid.UpdateOpts(<?php echo $image_upload_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($image_upload->CurrentMode == "add" || $image_upload->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $image_upload_grid->FormKeyCountName ?>" id="<?php echo $image_upload_grid->FormKeyCountName ?>" value="<?php echo $image_upload_grid->KeyCount ?>">
<?php echo $image_upload_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($image_upload->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $image_upload_grid->FormKeyCountName ?>" id="<?php echo $image_upload_grid->FormKeyCountName ?>" value="<?php echo $image_upload_grid->KeyCount ?>">
<?php echo $image_upload_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($image_upload->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fimage_uploadgrid">
</div>
<?php

// Close recordset
if ($image_upload_grid->Recordset)
	$image_upload_grid->Recordset->Close();
?>
<?php if ($image_upload_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($image_upload_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($image_upload_grid->TotalRecs == 0 && $image_upload->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($image_upload_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($image_upload->Export == "") { ?>
<script type="text/javascript">
fimage_uploadgrid.Init();
</script>
<?php } ?>
<?php
$image_upload_grid->Page_Terminate();
?>
