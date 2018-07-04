<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($blog_has_image_upload_grid)) $blog_has_image_upload_grid = new cblog_has_image_upload_grid();

// Page init
$blog_has_image_upload_grid->Page_Init();

// Page main
$blog_has_image_upload_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$blog_has_image_upload_grid->Page_Render();
?>
<?php if ($blog_has_image_upload->Export == "") { ?>
<script type="text/javascript">

// Form object
var fblog_has_image_uploadgrid = new ew_Form("fblog_has_image_uploadgrid", "grid");
fblog_has_image_uploadgrid.FormKeyCountName = '<?php echo $blog_has_image_upload_grid->FormKeyCountName ?>';

// Validate form
fblog_has_image_uploadgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_blog_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $blog_has_image_upload->blog_id->FldCaption(), $blog_has_image_upload->blog_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_blog_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($blog_has_image_upload->blog_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $blog_has_image_upload->id->FldCaption(), $blog_has_image_upload->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($blog_has_image_upload->id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fblog_has_image_uploadgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "blog_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id", false)) return false;
	return true;
}

// Form_CustomValidate event
fblog_has_image_uploadgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fblog_has_image_uploadgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($blog_has_image_upload->CurrentAction == "gridadd") {
	if ($blog_has_image_upload->CurrentMode == "copy") {
		$bSelectLimit = $blog_has_image_upload_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$blog_has_image_upload_grid->TotalRecs = $blog_has_image_upload->ListRecordCount();
			$blog_has_image_upload_grid->Recordset = $blog_has_image_upload_grid->LoadRecordset($blog_has_image_upload_grid->StartRec-1, $blog_has_image_upload_grid->DisplayRecs);
		} else {
			if ($blog_has_image_upload_grid->Recordset = $blog_has_image_upload_grid->LoadRecordset())
				$blog_has_image_upload_grid->TotalRecs = $blog_has_image_upload_grid->Recordset->RecordCount();
		}
		$blog_has_image_upload_grid->StartRec = 1;
		$blog_has_image_upload_grid->DisplayRecs = $blog_has_image_upload_grid->TotalRecs;
	} else {
		$blog_has_image_upload->CurrentFilter = "0=1";
		$blog_has_image_upload_grid->StartRec = 1;
		$blog_has_image_upload_grid->DisplayRecs = $blog_has_image_upload->GridAddRowCount;
	}
	$blog_has_image_upload_grid->TotalRecs = $blog_has_image_upload_grid->DisplayRecs;
	$blog_has_image_upload_grid->StopRec = $blog_has_image_upload_grid->DisplayRecs;
} else {
	$bSelectLimit = $blog_has_image_upload_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($blog_has_image_upload_grid->TotalRecs <= 0)
			$blog_has_image_upload_grid->TotalRecs = $blog_has_image_upload->ListRecordCount();
	} else {
		if (!$blog_has_image_upload_grid->Recordset && ($blog_has_image_upload_grid->Recordset = $blog_has_image_upload_grid->LoadRecordset()))
			$blog_has_image_upload_grid->TotalRecs = $blog_has_image_upload_grid->Recordset->RecordCount();
	}
	$blog_has_image_upload_grid->StartRec = 1;
	$blog_has_image_upload_grid->DisplayRecs = $blog_has_image_upload_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$blog_has_image_upload_grid->Recordset = $blog_has_image_upload_grid->LoadRecordset($blog_has_image_upload_grid->StartRec-1, $blog_has_image_upload_grid->DisplayRecs);

	// Set no record found message
	if ($blog_has_image_upload->CurrentAction == "" && $blog_has_image_upload_grid->TotalRecs == 0) {
		if ($blog_has_image_upload_grid->SearchWhere == "0=101")
			$blog_has_image_upload_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$blog_has_image_upload_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$blog_has_image_upload_grid->RenderOtherOptions();
?>
<?php $blog_has_image_upload_grid->ShowPageHeader(); ?>
<?php
$blog_has_image_upload_grid->ShowMessage();
?>
<?php if ($blog_has_image_upload_grid->TotalRecs > 0 || $blog_has_image_upload->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($blog_has_image_upload_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> blog_has_image_upload">
<div id="fblog_has_image_uploadgrid" class="ewForm ewListForm form-inline">
<div id="gmp_blog_has_image_upload" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_blog_has_image_uploadgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$blog_has_image_upload_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$blog_has_image_upload_grid->RenderListOptions();

// Render list options (header, left)
$blog_has_image_upload_grid->ListOptions->Render("header", "left");
?>
<?php if ($blog_has_image_upload->blog_id->Visible) { // blog_id ?>
	<?php if ($blog_has_image_upload->SortUrl($blog_has_image_upload->blog_id) == "") { ?>
		<th data-name="blog_id" class="<?php echo $blog_has_image_upload->blog_id->HeaderCellClass() ?>"><div id="elh_blog_has_image_upload_blog_id" class="blog_has_image_upload_blog_id"><div class="ewTableHeaderCaption"><?php echo $blog_has_image_upload->blog_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="blog_id" class="<?php echo $blog_has_image_upload->blog_id->HeaderCellClass() ?>"><div><div id="elh_blog_has_image_upload_blog_id" class="blog_has_image_upload_blog_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $blog_has_image_upload->blog_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($blog_has_image_upload->blog_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($blog_has_image_upload->blog_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($blog_has_image_upload->id->Visible) { // id ?>
	<?php if ($blog_has_image_upload->SortUrl($blog_has_image_upload->id) == "") { ?>
		<th data-name="id" class="<?php echo $blog_has_image_upload->id->HeaderCellClass() ?>"><div id="elh_blog_has_image_upload_id" class="blog_has_image_upload_id"><div class="ewTableHeaderCaption"><?php echo $blog_has_image_upload->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $blog_has_image_upload->id->HeaderCellClass() ?>"><div><div id="elh_blog_has_image_upload_id" class="blog_has_image_upload_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $blog_has_image_upload->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($blog_has_image_upload->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($blog_has_image_upload->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$blog_has_image_upload_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$blog_has_image_upload_grid->StartRec = 1;
$blog_has_image_upload_grid->StopRec = $blog_has_image_upload_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($blog_has_image_upload_grid->FormKeyCountName) && ($blog_has_image_upload->CurrentAction == "gridadd" || $blog_has_image_upload->CurrentAction == "gridedit" || $blog_has_image_upload->CurrentAction == "F")) {
		$blog_has_image_upload_grid->KeyCount = $objForm->GetValue($blog_has_image_upload_grid->FormKeyCountName);
		$blog_has_image_upload_grid->StopRec = $blog_has_image_upload_grid->StartRec + $blog_has_image_upload_grid->KeyCount - 1;
	}
}
$blog_has_image_upload_grid->RecCnt = $blog_has_image_upload_grid->StartRec - 1;
if ($blog_has_image_upload_grid->Recordset && !$blog_has_image_upload_grid->Recordset->EOF) {
	$blog_has_image_upload_grid->Recordset->MoveFirst();
	$bSelectLimit = $blog_has_image_upload_grid->UseSelectLimit;
	if (!$bSelectLimit && $blog_has_image_upload_grid->StartRec > 1)
		$blog_has_image_upload_grid->Recordset->Move($blog_has_image_upload_grid->StartRec - 1);
} elseif (!$blog_has_image_upload->AllowAddDeleteRow && $blog_has_image_upload_grid->StopRec == 0) {
	$blog_has_image_upload_grid->StopRec = $blog_has_image_upload->GridAddRowCount;
}

// Initialize aggregate
$blog_has_image_upload->RowType = EW_ROWTYPE_AGGREGATEINIT;
$blog_has_image_upload->ResetAttrs();
$blog_has_image_upload_grid->RenderRow();
if ($blog_has_image_upload->CurrentAction == "gridadd")
	$blog_has_image_upload_grid->RowIndex = 0;
if ($blog_has_image_upload->CurrentAction == "gridedit")
	$blog_has_image_upload_grid->RowIndex = 0;
while ($blog_has_image_upload_grid->RecCnt < $blog_has_image_upload_grid->StopRec) {
	$blog_has_image_upload_grid->RecCnt++;
	if (intval($blog_has_image_upload_grid->RecCnt) >= intval($blog_has_image_upload_grid->StartRec)) {
		$blog_has_image_upload_grid->RowCnt++;
		if ($blog_has_image_upload->CurrentAction == "gridadd" || $blog_has_image_upload->CurrentAction == "gridedit" || $blog_has_image_upload->CurrentAction == "F") {
			$blog_has_image_upload_grid->RowIndex++;
			$objForm->Index = $blog_has_image_upload_grid->RowIndex;
			if ($objForm->HasValue($blog_has_image_upload_grid->FormActionName))
				$blog_has_image_upload_grid->RowAction = strval($objForm->GetValue($blog_has_image_upload_grid->FormActionName));
			elseif ($blog_has_image_upload->CurrentAction == "gridadd")
				$blog_has_image_upload_grid->RowAction = "insert";
			else
				$blog_has_image_upload_grid->RowAction = "";
		}

		// Set up key count
		$blog_has_image_upload_grid->KeyCount = $blog_has_image_upload_grid->RowIndex;

		// Init row class and style
		$blog_has_image_upload->ResetAttrs();
		$blog_has_image_upload->CssClass = "";
		if ($blog_has_image_upload->CurrentAction == "gridadd") {
			if ($blog_has_image_upload->CurrentMode == "copy") {
				$blog_has_image_upload_grid->LoadRowValues($blog_has_image_upload_grid->Recordset); // Load row values
				$blog_has_image_upload_grid->SetRecordKey($blog_has_image_upload_grid->RowOldKey, $blog_has_image_upload_grid->Recordset); // Set old record key
			} else {
				$blog_has_image_upload_grid->LoadRowValues(); // Load default values
				$blog_has_image_upload_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$blog_has_image_upload_grid->LoadRowValues($blog_has_image_upload_grid->Recordset); // Load row values
		}
		$blog_has_image_upload->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($blog_has_image_upload->CurrentAction == "gridadd") // Grid add
			$blog_has_image_upload->RowType = EW_ROWTYPE_ADD; // Render add
		if ($blog_has_image_upload->CurrentAction == "gridadd" && $blog_has_image_upload->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$blog_has_image_upload_grid->RestoreCurrentRowFormValues($blog_has_image_upload_grid->RowIndex); // Restore form values
		if ($blog_has_image_upload->CurrentAction == "gridedit") { // Grid edit
			if ($blog_has_image_upload->EventCancelled) {
				$blog_has_image_upload_grid->RestoreCurrentRowFormValues($blog_has_image_upload_grid->RowIndex); // Restore form values
			}
			if ($blog_has_image_upload_grid->RowAction == "insert")
				$blog_has_image_upload->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$blog_has_image_upload->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($blog_has_image_upload->CurrentAction == "gridedit" && ($blog_has_image_upload->RowType == EW_ROWTYPE_EDIT || $blog_has_image_upload->RowType == EW_ROWTYPE_ADD) && $blog_has_image_upload->EventCancelled) // Update failed
			$blog_has_image_upload_grid->RestoreCurrentRowFormValues($blog_has_image_upload_grid->RowIndex); // Restore form values
		if ($blog_has_image_upload->RowType == EW_ROWTYPE_EDIT) // Edit row
			$blog_has_image_upload_grid->EditRowCnt++;
		if ($blog_has_image_upload->CurrentAction == "F") // Confirm row
			$blog_has_image_upload_grid->RestoreCurrentRowFormValues($blog_has_image_upload_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$blog_has_image_upload->RowAttrs = array_merge($blog_has_image_upload->RowAttrs, array('data-rowindex'=>$blog_has_image_upload_grid->RowCnt, 'id'=>'r' . $blog_has_image_upload_grid->RowCnt . '_blog_has_image_upload', 'data-rowtype'=>$blog_has_image_upload->RowType));

		// Render row
		$blog_has_image_upload_grid->RenderRow();

		// Render list options
		$blog_has_image_upload_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($blog_has_image_upload_grid->RowAction <> "delete" && $blog_has_image_upload_grid->RowAction <> "insertdelete" && !($blog_has_image_upload_grid->RowAction == "insert" && $blog_has_image_upload->CurrentAction == "F" && $blog_has_image_upload_grid->EmptyRow())) {
?>
	<tr<?php echo $blog_has_image_upload->RowAttributes() ?>>
<?php

// Render list options (body, left)
$blog_has_image_upload_grid->ListOptions->Render("body", "left", $blog_has_image_upload_grid->RowCnt);
?>
	<?php if ($blog_has_image_upload->blog_id->Visible) { // blog_id ?>
		<td data-name="blog_id"<?php echo $blog_has_image_upload->blog_id->CellAttributes() ?>>
<?php if ($blog_has_image_upload->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($blog_has_image_upload->blog_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $blog_has_image_upload_grid->RowCnt ?>_blog_has_image_upload_blog_id" class="form-group blog_has_image_upload_blog_id">
<span<?php echo $blog_has_image_upload->blog_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $blog_has_image_upload->blog_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $blog_has_image_upload_grid->RowCnt ?>_blog_has_image_upload_blog_id" class="form-group blog_has_image_upload_blog_id">
<input type="text" data-table="blog_has_image_upload" data-field="x_blog_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" size="30" placeholder="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->getPlaceHolder()) ?>" value="<?php echo $blog_has_image_upload->blog_id->EditValue ?>"<?php echo $blog_has_image_upload->blog_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_blog_id" name="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->OldValue) ?>">
<?php } ?>
<?php if ($blog_has_image_upload->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $blog_has_image_upload_grid->RowCnt ?>_blog_has_image_upload_blog_id" class="form-group blog_has_image_upload_blog_id">
<span<?php echo $blog_has_image_upload->blog_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $blog_has_image_upload->blog_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_blog_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->CurrentValue) ?>">
<?php } ?>
<?php if ($blog_has_image_upload->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $blog_has_image_upload_grid->RowCnt ?>_blog_has_image_upload_blog_id" class="blog_has_image_upload_blog_id">
<span<?php echo $blog_has_image_upload->blog_id->ViewAttributes() ?>>
<?php echo $blog_has_image_upload->blog_id->ListViewValue() ?></span>
</span>
<?php if ($blog_has_image_upload->CurrentAction <> "F") { ?>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_blog_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->FormValue) ?>">
<input type="hidden" data-table="blog_has_image_upload" data-field="x_blog_id" name="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_blog_id" name="fblog_has_image_uploadgrid$x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="fblog_has_image_uploadgrid$x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->FormValue) ?>">
<input type="hidden" data-table="blog_has_image_upload" data-field="x_blog_id" name="fblog_has_image_uploadgrid$o<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="fblog_has_image_uploadgrid$o<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($blog_has_image_upload->id->Visible) { // id ?>
		<td data-name="id"<?php echo $blog_has_image_upload->id->CellAttributes() ?>>
<?php if ($blog_has_image_upload->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($blog_has_image_upload->id->getSessionValue() <> "") { ?>
<span id="el<?php echo $blog_has_image_upload_grid->RowCnt ?>_blog_has_image_upload_id" class="form-group blog_has_image_upload_id">
<span<?php echo $blog_has_image_upload->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $blog_has_image_upload->id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $blog_has_image_upload_grid->RowCnt ?>_blog_has_image_upload_id" class="form-group blog_has_image_upload_id">
<input type="text" data-table="blog_has_image_upload" data-field="x_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" size="30" placeholder="<?php echo ew_HtmlEncode($blog_has_image_upload->id->getPlaceHolder()) ?>" value="<?php echo $blog_has_image_upload->id->EditValue ?>"<?php echo $blog_has_image_upload->id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_id" name="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->OldValue) ?>">
<?php } ?>
<?php if ($blog_has_image_upload->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $blog_has_image_upload_grid->RowCnt ?>_blog_has_image_upload_id" class="form-group blog_has_image_upload_id">
<span<?php echo $blog_has_image_upload->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $blog_has_image_upload->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->CurrentValue) ?>">
<?php } ?>
<?php if ($blog_has_image_upload->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $blog_has_image_upload_grid->RowCnt ?>_blog_has_image_upload_id" class="blog_has_image_upload_id">
<span<?php echo $blog_has_image_upload->id->ViewAttributes() ?>>
<?php echo $blog_has_image_upload->id->ListViewValue() ?></span>
</span>
<?php if ($blog_has_image_upload->CurrentAction <> "F") { ?>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->FormValue) ?>">
<input type="hidden" data-table="blog_has_image_upload" data-field="x_id" name="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_id" name="fblog_has_image_uploadgrid$x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="fblog_has_image_uploadgrid$x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->FormValue) ?>">
<input type="hidden" data-table="blog_has_image_upload" data-field="x_id" name="fblog_has_image_uploadgrid$o<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="fblog_has_image_uploadgrid$o<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$blog_has_image_upload_grid->ListOptions->Render("body", "right", $blog_has_image_upload_grid->RowCnt);
?>
	</tr>
<?php if ($blog_has_image_upload->RowType == EW_ROWTYPE_ADD || $blog_has_image_upload->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fblog_has_image_uploadgrid.UpdateOpts(<?php echo $blog_has_image_upload_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($blog_has_image_upload->CurrentAction <> "gridadd" || $blog_has_image_upload->CurrentMode == "copy")
		if (!$blog_has_image_upload_grid->Recordset->EOF) $blog_has_image_upload_grid->Recordset->MoveNext();
}
?>
<?php
	if ($blog_has_image_upload->CurrentMode == "add" || $blog_has_image_upload->CurrentMode == "copy" || $blog_has_image_upload->CurrentMode == "edit") {
		$blog_has_image_upload_grid->RowIndex = '$rowindex$';
		$blog_has_image_upload_grid->LoadRowValues();

		// Set row properties
		$blog_has_image_upload->ResetAttrs();
		$blog_has_image_upload->RowAttrs = array_merge($blog_has_image_upload->RowAttrs, array('data-rowindex'=>$blog_has_image_upload_grid->RowIndex, 'id'=>'r0_blog_has_image_upload', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($blog_has_image_upload->RowAttrs["class"], "ewTemplate");
		$blog_has_image_upload->RowType = EW_ROWTYPE_ADD;

		// Render row
		$blog_has_image_upload_grid->RenderRow();

		// Render list options
		$blog_has_image_upload_grid->RenderListOptions();
		$blog_has_image_upload_grid->StartRowCnt = 0;
?>
	<tr<?php echo $blog_has_image_upload->RowAttributes() ?>>
<?php

// Render list options (body, left)
$blog_has_image_upload_grid->ListOptions->Render("body", "left", $blog_has_image_upload_grid->RowIndex);
?>
	<?php if ($blog_has_image_upload->blog_id->Visible) { // blog_id ?>
		<td data-name="blog_id">
<?php if ($blog_has_image_upload->CurrentAction <> "F") { ?>
<?php if ($blog_has_image_upload->blog_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_blog_has_image_upload_blog_id" class="form-group blog_has_image_upload_blog_id">
<span<?php echo $blog_has_image_upload->blog_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $blog_has_image_upload->blog_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_blog_has_image_upload_blog_id" class="form-group blog_has_image_upload_blog_id">
<input type="text" data-table="blog_has_image_upload" data-field="x_blog_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" size="30" placeholder="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->getPlaceHolder()) ?>" value="<?php echo $blog_has_image_upload->blog_id->EditValue ?>"<?php echo $blog_has_image_upload->blog_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_blog_has_image_upload_blog_id" class="form-group blog_has_image_upload_blog_id">
<span<?php echo $blog_has_image_upload->blog_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $blog_has_image_upload->blog_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_blog_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_blog_id" name="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" id="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_blog_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->blog_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($blog_has_image_upload->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($blog_has_image_upload->CurrentAction <> "F") { ?>
<?php if ($blog_has_image_upload->id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_blog_has_image_upload_id" class="form-group blog_has_image_upload_id">
<span<?php echo $blog_has_image_upload->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $blog_has_image_upload->id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_blog_has_image_upload_id" class="form-group blog_has_image_upload_id">
<input type="text" data-table="blog_has_image_upload" data-field="x_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" size="30" placeholder="<?php echo ew_HtmlEncode($blog_has_image_upload->id->getPlaceHolder()) ?>" value="<?php echo $blog_has_image_upload->id->EditValue ?>"<?php echo $blog_has_image_upload->id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_blog_has_image_upload_id" class="form-group blog_has_image_upload_id">
<span<?php echo $blog_has_image_upload->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $blog_has_image_upload->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_id" name="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="x<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="blog_has_image_upload" data-field="x_id" name="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" id="o<?php echo $blog_has_image_upload_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($blog_has_image_upload->id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$blog_has_image_upload_grid->ListOptions->Render("body", "right", $blog_has_image_upload_grid->RowIndex);
?>
<script type="text/javascript">
fblog_has_image_uploadgrid.UpdateOpts(<?php echo $blog_has_image_upload_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($blog_has_image_upload->CurrentMode == "add" || $blog_has_image_upload->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $blog_has_image_upload_grid->FormKeyCountName ?>" id="<?php echo $blog_has_image_upload_grid->FormKeyCountName ?>" value="<?php echo $blog_has_image_upload_grid->KeyCount ?>">
<?php echo $blog_has_image_upload_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($blog_has_image_upload->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $blog_has_image_upload_grid->FormKeyCountName ?>" id="<?php echo $blog_has_image_upload_grid->FormKeyCountName ?>" value="<?php echo $blog_has_image_upload_grid->KeyCount ?>">
<?php echo $blog_has_image_upload_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($blog_has_image_upload->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fblog_has_image_uploadgrid">
</div>
<?php

// Close recordset
if ($blog_has_image_upload_grid->Recordset)
	$blog_has_image_upload_grid->Recordset->Close();
?>
<?php if ($blog_has_image_upload_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($blog_has_image_upload_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($blog_has_image_upload_grid->TotalRecs == 0 && $blog_has_image_upload->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($blog_has_image_upload_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($blog_has_image_upload->Export == "") { ?>
<script type="text/javascript">
fblog_has_image_uploadgrid.Init();
</script>
<?php } ?>
<?php
$blog_has_image_upload_grid->Page_Terminate();
?>
