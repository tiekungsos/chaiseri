<?php

// Global variable for table object
$blog = NULL;

//
// Table class for blog
//
class cblog extends cTable {
	var $blog_id;
	var $name;
	var $description_head;
	var $description;
	var $date;
	var $date_create;
	var $image_upload;
	var $image_upload_head;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'blog';
		$this->TableName = 'blog';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`blog`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 1;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// blog_id
		$this->blog_id = new cField('blog', 'blog', 'x_blog_id', 'blog_id', '`blog_id`', '`blog_id`', 3, -1, FALSE, '`blog_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->blog_id->Sortable = TRUE; // Allow sort
		$this->blog_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['blog_id'] = &$this->blog_id;

		// name
		$this->name = new cField('blog', 'blog', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->name->Sortable = TRUE; // Allow sort
		$this->fields['name'] = &$this->name;

		// description_head
		$this->description_head = new cField('blog', 'blog', 'x_description_head', 'description_head', '`description_head`', '`description_head`', 200, -1, FALSE, '`description_head`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->description_head->Sortable = TRUE; // Allow sort
		$this->fields['description_head'] = &$this->description_head;

		// description
		$this->description = new cField('blog', 'blog', 'x_description', 'description', '`description`', '`description`', 201, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->description->Sortable = TRUE; // Allow sort
		$this->fields['description'] = &$this->description;

		// date
		$this->date = new cField('blog', 'blog', 'x_date', 'date', '`date`', ew_CastDateFieldForLike('`date`', 0, "DB"), 133, 0, FALSE, '`date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date->Sortable = TRUE; // Allow sort
		$this->date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date'] = &$this->date;

		// date_create
		$this->date_create = new cField('blog', 'blog', 'x_date_create', 'date_create', '`date_create`', ew_CastDateFieldForLike('`date_create`', 0, "DB"), 135, 0, FALSE, '`date_create`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_create->Sortable = TRUE; // Allow sort
		$this->date_create->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_create'] = &$this->date_create;

		// image_upload
		$this->image_upload = new cField('blog', 'blog', 'x_image_upload', 'image_upload', '`image_upload`', '`image_upload`', 200, -1, TRUE, '`image_upload`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->image_upload->Sortable = TRUE; // Allow sort
		$this->image_upload->UploadMultiple = TRUE;
		$this->image_upload->Upload->UploadMultiple = TRUE;
		$this->image_upload->UploadMaxFileCount = 0;
		$this->fields['image_upload'] = &$this->image_upload;

		// image_upload_head
		$this->image_upload_head = new cField('blog', 'blog', 'x_image_upload_head', 'image_upload_head', '`image_upload_head`', '`image_upload_head`', 200, -1, TRUE, '`image_upload_head`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->image_upload_head->Sortable = TRUE; // Allow sort
		$this->fields['image_upload_head'] = &$this->image_upload_head;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`blog`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->blog_id->setDbValue($conn->Insert_ID());
			$rs['blog_id'] = $this->blog_id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('blog_id', $rs))
				ew_AddFilter($where, ew_QuotedName('blog_id', $this->DBID) . '=' . ew_QuotedValue($rs['blog_id'], $this->blog_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`blog_id` = @blog_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->blog_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->blog_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@blog_id@", ew_AdjustSql($this->blog_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "bloglist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "blogview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "blogedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "blogadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "bloglist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("blogview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("blogview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "blogadd.php?" . $this->UrlParm($parm);
		else
			$url = "blogadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("blogedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("blogadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("blogdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "blog_id:" . ew_VarToJson($this->blog_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->blog_id->CurrentValue)) {
			$sUrl .= "blog_id=" . urlencode($this->blog_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["blog_id"]))
				$arKeys[] = $_POST["blog_id"];
			elseif (isset($_GET["blog_id"]))
				$arKeys[] = $_GET["blog_id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->blog_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->blog_id->setDbValue($rs->fields('blog_id'));
		$this->name->setDbValue($rs->fields('name'));
		$this->description_head->setDbValue($rs->fields('description_head'));
		$this->description->setDbValue($rs->fields('description'));
		$this->date->setDbValue($rs->fields('date'));
		$this->date_create->setDbValue($rs->fields('date_create'));
		$this->image_upload->Upload->DbValue = $rs->fields('image_upload');
		$this->image_upload_head->Upload->DbValue = $rs->fields('image_upload_head');
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// blog_id
		// name
		// description_head
		// description
		// date
		// date_create
		// image_upload
		// image_upload_head
		// blog_id

		$this->blog_id->ViewValue = $this->blog_id->CurrentValue;
		$this->blog_id->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// description_head
		$this->description_head->ViewValue = $this->description_head->CurrentValue;
		$this->description_head->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		if (!is_null($this->description->ViewValue)) $this->description->ViewValue = str_replace("\n", "<br>", $this->description->ViewValue);
		$this->description->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// date_create
		$this->date_create->ViewValue = $this->date_create->CurrentValue;
		$this->date_create->ViewValue = ew_FormatDateTime($this->date_create->ViewValue, 0);
		$this->date_create->ViewCustomAttributes = "";

		// image_upload
		if (!ew_Empty($this->image_upload->Upload->DbValue)) {
			$this->image_upload->ViewValue = $this->image_upload->Upload->DbValue;
		} else {
			$this->image_upload->ViewValue = "";
		}
		$this->image_upload->ViewCustomAttributes = "";

		// image_upload_head
		if (!ew_Empty($this->image_upload_head->Upload->DbValue)) {
			$this->image_upload_head->ViewValue = $this->image_upload_head->Upload->DbValue;
		} else {
			$this->image_upload_head->ViewValue = "";
		}
		$this->image_upload_head->ViewCustomAttributes = "";

		// blog_id
		$this->blog_id->LinkCustomAttributes = "";
		$this->blog_id->HrefValue = "";
		$this->blog_id->TooltipValue = "";

		// name
		$this->name->LinkCustomAttributes = "";
		$this->name->HrefValue = "";
		$this->name->TooltipValue = "";

		// description_head
		$this->description_head->LinkCustomAttributes = "";
		$this->description_head->HrefValue = "";
		$this->description_head->TooltipValue = "";

		// description
		$this->description->LinkCustomAttributes = "";
		$this->description->HrefValue = "";
		$this->description->TooltipValue = "";

		// date
		$this->date->LinkCustomAttributes = "";
		$this->date->HrefValue = "";
		$this->date->TooltipValue = "";

		// date_create
		$this->date_create->LinkCustomAttributes = "";
		$this->date_create->HrefValue = "";
		$this->date_create->TooltipValue = "";

		// image_upload
		$this->image_upload->LinkCustomAttributes = "";
		$this->image_upload->HrefValue = "";
		$this->image_upload->HrefValue2 = $this->image_upload->UploadPath . $this->image_upload->Upload->DbValue;
		$this->image_upload->TooltipValue = "";

		// image_upload_head
		$this->image_upload_head->LinkCustomAttributes = "";
		$this->image_upload_head->HrefValue = "";
		$this->image_upload_head->HrefValue2 = $this->image_upload_head->UploadPath . $this->image_upload_head->Upload->DbValue;
		$this->image_upload_head->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// blog_id
		$this->blog_id->EditAttrs["class"] = "form-control";
		$this->blog_id->EditCustomAttributes = "";
		$this->blog_id->EditValue = $this->blog_id->CurrentValue;
		$this->blog_id->ViewCustomAttributes = "";

		// name
		$this->name->EditAttrs["class"] = "form-control";
		$this->name->EditCustomAttributes = "";
		$this->name->EditValue = $this->name->CurrentValue;
		$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

		// description_head
		$this->description_head->EditAttrs["class"] = "form-control";
		$this->description_head->EditCustomAttributes = 255;
		$this->description_head->EditValue = $this->description_head->CurrentValue;
		$this->description_head->PlaceHolder = ew_RemoveHtml($this->description_head->FldCaption());

		// description
		$this->description->EditAttrs["class"] = "form-control";
		$this->description->EditCustomAttributes = 1000;
		$this->description->EditValue = $this->description->CurrentValue;
		$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

		// date
		$this->date->EditAttrs["class"] = "form-control";
		$this->date->EditCustomAttributes = "";
		$this->date->EditValue = ew_FormatDateTime($this->date->CurrentValue, 8);
		$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

		// date_create
		// image_upload

		$this->image_upload->EditAttrs["class"] = "form-control";
		$this->image_upload->EditCustomAttributes = "";
		if (!ew_Empty($this->image_upload->Upload->DbValue)) {
			$this->image_upload->EditValue = $this->image_upload->Upload->DbValue;
		} else {
			$this->image_upload->EditValue = "";
		}
		if (!ew_Empty($this->image_upload->CurrentValue))
				$this->image_upload->Upload->FileName = $this->image_upload->CurrentValue;

		// image_upload_head
		$this->image_upload_head->EditAttrs["class"] = "form-control";
		$this->image_upload_head->EditCustomAttributes = "";
		if (!ew_Empty($this->image_upload_head->Upload->DbValue)) {
			$this->image_upload_head->EditValue = $this->image_upload_head->Upload->DbValue;
		} else {
			$this->image_upload_head->EditValue = "";
		}
		if (!ew_Empty($this->image_upload_head->CurrentValue))
				$this->image_upload_head->Upload->FileName = $this->image_upload_head->CurrentValue;

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->blog_id->Exportable) $Doc->ExportCaption($this->blog_id);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->description_head->Exportable) $Doc->ExportCaption($this->description_head);
					if ($this->description->Exportable) $Doc->ExportCaption($this->description);
					if ($this->date->Exportable) $Doc->ExportCaption($this->date);
					if ($this->date_create->Exportable) $Doc->ExportCaption($this->date_create);
					if ($this->image_upload->Exportable) $Doc->ExportCaption($this->image_upload);
					if ($this->image_upload_head->Exportable) $Doc->ExportCaption($this->image_upload_head);
				} else {
					if ($this->blog_id->Exportable) $Doc->ExportCaption($this->blog_id);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->description_head->Exportable) $Doc->ExportCaption($this->description_head);
					if ($this->date->Exportable) $Doc->ExportCaption($this->date);
					if ($this->date_create->Exportable) $Doc->ExportCaption($this->date_create);
					if ($this->image_upload->Exportable) $Doc->ExportCaption($this->image_upload);
					if ($this->image_upload_head->Exportable) $Doc->ExportCaption($this->image_upload_head);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->blog_id->Exportable) $Doc->ExportField($this->blog_id);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->description_head->Exportable) $Doc->ExportField($this->description_head);
						if ($this->description->Exportable) $Doc->ExportField($this->description);
						if ($this->date->Exportable) $Doc->ExportField($this->date);
						if ($this->date_create->Exportable) $Doc->ExportField($this->date_create);
						if ($this->image_upload->Exportable) $Doc->ExportField($this->image_upload);
						if ($this->image_upload_head->Exportable) $Doc->ExportField($this->image_upload_head);
					} else {
						if ($this->blog_id->Exportable) $Doc->ExportField($this->blog_id);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->description_head->Exportable) $Doc->ExportField($this->description_head);
						if ($this->date->Exportable) $Doc->ExportField($this->date);
						if ($this->date_create->Exportable) $Doc->ExportField($this->date_create);
						if ($this->image_upload->Exportable) $Doc->ExportField($this->image_upload);
						if ($this->image_upload_head->Exportable) $Doc->ExportField($this->image_upload_head);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
