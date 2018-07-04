<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "productinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$product_edit = NULL; // Initialize page object first

class cproduct_edit extends cproduct {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{5E0F01B1-4565-4ABF-9ED4-51691E2A8222}';

	// Table name
	var $TableName = 'product';

	// Page object name
	var $PageObjName = 'product_edit';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (product)
		if (!isset($GLOBALS["product"]) || get_class($GLOBALS["product"]) == "cproduct") {
			$GLOBALS["product"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["product"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'product', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (user)
		if (!isset($UserTable)) {
			$UserTable = new cuser();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("productlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->product_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->product_id->Visible = FALSE;
		$this->name_th->SetVisibility();
		$this->description_th->SetVisibility();
		$this->name_en->SetVisibility();
		$this->description_en->SetVisibility();
		$this->size->SetVisibility();
		$this->type->SetVisibility();
		$this->brand->SetVisibility();
		$this->create_date->SetVisibility();
		$this->img->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $product;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($product);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "productview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_product_id")) {
				$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["product_id"])) {
				$this->product_id->setQueryStringValue($_GET["product_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->product_id->CurrentValue = NULL;
			}
		}

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("productlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "productlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->img->Upload->Index = $objForm->Index;
		$this->img->Upload->UploadFile();
		$this->img->CurrentValue = $this->img->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->product_id->FldIsDetailKey)
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		if (!$this->name_th->FldIsDetailKey) {
			$this->name_th->setFormValue($objForm->GetValue("x_name_th"));
		}
		if (!$this->description_th->FldIsDetailKey) {
			$this->description_th->setFormValue($objForm->GetValue("x_description_th"));
		}
		if (!$this->name_en->FldIsDetailKey) {
			$this->name_en->setFormValue($objForm->GetValue("x_name_en"));
		}
		if (!$this->description_en->FldIsDetailKey) {
			$this->description_en->setFormValue($objForm->GetValue("x_description_en"));
		}
		if (!$this->size->FldIsDetailKey) {
			$this->size->setFormValue($objForm->GetValue("x_size"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->brand->FldIsDetailKey) {
			$this->brand->setFormValue($objForm->GetValue("x_brand"));
		}
		if (!$this->create_date->FldIsDetailKey) {
			$this->create_date->setFormValue($objForm->GetValue("x_create_date"));
			$this->create_date->CurrentValue = ew_UnFormatDateTime($this->create_date->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->name_th->CurrentValue = $this->name_th->FormValue;
		$this->description_th->CurrentValue = $this->description_th->FormValue;
		$this->name_en->CurrentValue = $this->name_en->FormValue;
		$this->description_en->CurrentValue = $this->description_en->FormValue;
		$this->size->CurrentValue = $this->size->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->brand->CurrentValue = $this->brand->FormValue;
		$this->create_date->CurrentValue = $this->create_date->FormValue;
		$this->create_date->CurrentValue = ew_UnFormatDateTime($this->create_date->CurrentValue, 0);
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->product_id->setDbValue($row['product_id']);
		$this->name_th->setDbValue($row['name_th']);
		$this->description_th->setDbValue($row['description_th']);
		$this->name_en->setDbValue($row['name_en']);
		$this->description_en->setDbValue($row['description_en']);
		$this->size->setDbValue($row['size']);
		$this->type->setDbValue($row['type']);
		$this->brand->setDbValue($row['brand']);
		$this->create_date->setDbValue($row['create_date']);
		$this->img->Upload->DbValue = $row['img'];
		$this->img->setDbValue($this->img->Upload->DbValue);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['product_id'] = NULL;
		$row['name_th'] = NULL;
		$row['description_th'] = NULL;
		$row['name_en'] = NULL;
		$row['description_en'] = NULL;
		$row['size'] = NULL;
		$row['type'] = NULL;
		$row['brand'] = NULL;
		$row['create_date'] = NULL;
		$row['img'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->product_id->DbValue = $row['product_id'];
		$this->name_th->DbValue = $row['name_th'];
		$this->description_th->DbValue = $row['description_th'];
		$this->name_en->DbValue = $row['name_en'];
		$this->description_en->DbValue = $row['description_en'];
		$this->size->DbValue = $row['size'];
		$this->type->DbValue = $row['type'];
		$this->brand->DbValue = $row['brand'];
		$this->create_date->DbValue = $row['create_date'];
		$this->img->Upload->DbValue = $row['img'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("product_id")) <> "")
			$this->product_id->CurrentValue = $this->getKey("product_id"); // product_id
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// product_id
		// name_th
		// description_th
		// name_en
		// description_en
		// size
		// type
		// brand
		// create_date
		// img

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// product_id
		$this->product_id->ViewValue = $this->product_id->CurrentValue;
		$this->product_id->ViewCustomAttributes = "";

		// name_th
		$this->name_th->ViewValue = $this->name_th->CurrentValue;
		$this->name_th->ViewCustomAttributes = "";

		// description_th
		$this->description_th->ViewValue = $this->description_th->CurrentValue;
		$this->description_th->ViewCustomAttributes = "";

		// name_en
		$this->name_en->ViewValue = $this->name_en->CurrentValue;
		$this->name_en->ViewCustomAttributes = "";

		// description_en
		$this->description_en->ViewValue = $this->description_en->CurrentValue;
		$this->description_en->ViewCustomAttributes = "";

		// size
		$this->size->ViewValue = $this->size->CurrentValue;
		$this->size->ViewValue = ew_FormatNumber($this->size->ViewValue, 0, -1, -2, -2);
		$this->size->ViewCustomAttributes = "";

		// type
		if (strval($this->type->CurrentValue) <> "") {
			$this->type->ViewValue = $this->type->OptionCaption($this->type->CurrentValue);
		} else {
			$this->type->ViewValue = NULL;
		}
		$this->type->ViewCustomAttributes = "";

		// brand
		if (strval($this->brand->CurrentValue) <> "") {
			$this->brand->ViewValue = $this->brand->OptionCaption($this->brand->CurrentValue);
		} else {
			$this->brand->ViewValue = NULL;
		}
		$this->brand->ViewCustomAttributes = "";

		// create_date
		$this->create_date->ViewValue = $this->create_date->CurrentValue;
		$this->create_date->ViewValue = ew_FormatDateTime($this->create_date->ViewValue, 0);
		$this->create_date->ViewCustomAttributes = "";

		// img
		if (!ew_Empty($this->img->Upload->DbValue)) {
			$this->img->ImageAlt = $this->img->FldAlt();
			$this->img->ViewValue = $this->img->Upload->DbValue;
		} else {
			$this->img->ViewValue = "";
		}
		$this->img->ViewCustomAttributes = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";
			$this->product_id->TooltipValue = "";

			// name_th
			$this->name_th->LinkCustomAttributes = "";
			$this->name_th->HrefValue = "";
			$this->name_th->TooltipValue = "";

			// description_th
			$this->description_th->LinkCustomAttributes = "";
			$this->description_th->HrefValue = "";
			$this->description_th->TooltipValue = "";

			// name_en
			$this->name_en->LinkCustomAttributes = "";
			$this->name_en->HrefValue = "";
			$this->name_en->TooltipValue = "";

			// description_en
			$this->description_en->LinkCustomAttributes = "";
			$this->description_en->HrefValue = "";
			$this->description_en->TooltipValue = "";

			// size
			$this->size->LinkCustomAttributes = "";
			$this->size->HrefValue = "";
			$this->size->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// brand
			$this->brand->LinkCustomAttributes = "";
			$this->brand->HrefValue = "";
			$this->brand->TooltipValue = "";

			// create_date
			$this->create_date->LinkCustomAttributes = "";
			$this->create_date->HrefValue = "";
			$this->create_date->TooltipValue = "";

			// img
			$this->img->LinkCustomAttributes = "";
			if (!ew_Empty($this->img->Upload->DbValue)) {
				$this->img->HrefValue = ew_GetFileUploadUrl($this->img, $this->img->Upload->DbValue); // Add prefix/suffix
				$this->img->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->img->HrefValue = ew_FullUrl($this->img->HrefValue, "href");
			} else {
				$this->img->HrefValue = "";
			}
			$this->img->HrefValue2 = $this->img->UploadPath . $this->img->Upload->DbValue;
			$this->img->TooltipValue = "";
			if ($this->img->UseColorbox) {
				if (ew_Empty($this->img->TooltipValue))
					$this->img->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->img->LinkAttrs["data-rel"] = "product_x_img";
				ew_AppendClass($this->img->LinkAttrs["class"], "ewLightbox");
			}
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// product_id
			$this->product_id->EditAttrs["class"] = "form-control";
			$this->product_id->EditCustomAttributes = "";
			$this->product_id->EditValue = $this->product_id->CurrentValue;
			$this->product_id->ViewCustomAttributes = "";

			// name_th
			$this->name_th->EditAttrs["class"] = "form-control";
			$this->name_th->EditCustomAttributes = "";
			$this->name_th->EditValue = ew_HtmlEncode($this->name_th->CurrentValue);
			$this->name_th->PlaceHolder = ew_RemoveHtml($this->name_th->FldCaption());

			// description_th
			$this->description_th->EditAttrs["class"] = "form-control";
			$this->description_th->EditCustomAttributes = 255;
			$this->description_th->EditValue = ew_HtmlEncode($this->description_th->CurrentValue);
			$this->description_th->PlaceHolder = ew_RemoveHtml($this->description_th->FldCaption());

			// name_en
			$this->name_en->EditAttrs["class"] = "form-control";
			$this->name_en->EditCustomAttributes = "";
			$this->name_en->EditValue = ew_HtmlEncode($this->name_en->CurrentValue);
			$this->name_en->PlaceHolder = ew_RemoveHtml($this->name_en->FldCaption());

			// description_en
			$this->description_en->EditAttrs["class"] = "form-control";
			$this->description_en->EditCustomAttributes = 255;
			$this->description_en->EditValue = ew_HtmlEncode($this->description_en->CurrentValue);
			$this->description_en->PlaceHolder = ew_RemoveHtml($this->description_en->FldCaption());

			// size
			$this->size->EditAttrs["class"] = "form-control";
			$this->size->EditCustomAttributes = "";
			$this->size->EditValue = ew_HtmlEncode($this->size->CurrentValue);
			$this->size->PlaceHolder = ew_RemoveHtml($this->size->FldCaption());

			// type
			$this->type->EditAttrs["class"] = "form-control";
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = $this->type->Options(TRUE);

			// brand
			$this->brand->EditAttrs["class"] = "form-control";
			$this->brand->EditCustomAttributes = "";
			$this->brand->EditValue = $this->brand->Options(TRUE);

			// create_date
			// img

			$this->img->EditAttrs["class"] = "form-control";
			$this->img->EditCustomAttributes = "";
			if (!ew_Empty($this->img->Upload->DbValue)) {
				$this->img->ImageAlt = $this->img->FldAlt();
				$this->img->EditValue = $this->img->Upload->DbValue;
			} else {
				$this->img->EditValue = "";
			}
			if (!ew_Empty($this->img->CurrentValue))
					$this->img->Upload->FileName = $this->img->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->img);

			// Edit refer script
			// product_id

			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";

			// name_th
			$this->name_th->LinkCustomAttributes = "";
			$this->name_th->HrefValue = "";

			// description_th
			$this->description_th->LinkCustomAttributes = "";
			$this->description_th->HrefValue = "";

			// name_en
			$this->name_en->LinkCustomAttributes = "";
			$this->name_en->HrefValue = "";

			// description_en
			$this->description_en->LinkCustomAttributes = "";
			$this->description_en->HrefValue = "";

			// size
			$this->size->LinkCustomAttributes = "";
			$this->size->HrefValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";

			// brand
			$this->brand->LinkCustomAttributes = "";
			$this->brand->HrefValue = "";

			// create_date
			$this->create_date->LinkCustomAttributes = "";
			$this->create_date->HrefValue = "";

			// img
			$this->img->LinkCustomAttributes = "";
			if (!ew_Empty($this->img->Upload->DbValue)) {
				$this->img->HrefValue = ew_GetFileUploadUrl($this->img, $this->img->Upload->DbValue); // Add prefix/suffix
				$this->img->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->img->HrefValue = ew_FullUrl($this->img->HrefValue, "href");
			} else {
				$this->img->HrefValue = "";
			}
			$this->img->HrefValue2 = $this->img->UploadPath . $this->img->Upload->DbValue;
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->name_th->FldIsDetailKey && !is_null($this->name_th->FormValue) && $this->name_th->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name_th->FldCaption(), $this->name_th->ReqErrMsg));
		}
		if (!$this->description_th->FldIsDetailKey && !is_null($this->description_th->FormValue) && $this->description_th->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description_th->FldCaption(), $this->description_th->ReqErrMsg));
		}
		if (!$this->name_en->FldIsDetailKey && !is_null($this->name_en->FormValue) && $this->name_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name_en->FldCaption(), $this->name_en->ReqErrMsg));
		}
		if (!$this->description_en->FldIsDetailKey && !is_null($this->description_en->FormValue) && $this->description_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description_en->FldCaption(), $this->description_en->ReqErrMsg));
		}
		if (!$this->size->FldIsDetailKey && !is_null($this->size->FormValue) && $this->size->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->size->FldCaption(), $this->size->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->size->FormValue)) {
			ew_AddMessage($gsFormError, $this->size->FldErrMsg());
		}
		if (!$this->type->FldIsDetailKey && !is_null($this->type->FormValue) && $this->type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->type->FldCaption(), $this->type->ReqErrMsg));
		}
		if (!$this->brand->FldIsDetailKey && !is_null($this->brand->FormValue) && $this->brand->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->brand->FldCaption(), $this->brand->ReqErrMsg));
		}
		if ($this->img->Upload->FileName == "" && !$this->img->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->img->FldCaption(), $this->img->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// name_th
			$this->name_th->SetDbValueDef($rsnew, $this->name_th->CurrentValue, NULL, $this->name_th->ReadOnly);

			// description_th
			$this->description_th->SetDbValueDef($rsnew, $this->description_th->CurrentValue, NULL, $this->description_th->ReadOnly);

			// name_en
			$this->name_en->SetDbValueDef($rsnew, $this->name_en->CurrentValue, NULL, $this->name_en->ReadOnly);

			// description_en
			$this->description_en->SetDbValueDef($rsnew, $this->description_en->CurrentValue, NULL, $this->description_en->ReadOnly);

			// size
			$this->size->SetDbValueDef($rsnew, $this->size->CurrentValue, NULL, $this->size->ReadOnly);

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, NULL, $this->type->ReadOnly);

			// brand
			$this->brand->SetDbValueDef($rsnew, $this->brand->CurrentValue, NULL, $this->brand->ReadOnly);

			// create_date
			$this->create_date->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['create_date'] = &$this->create_date->DbValue;

			// img
			if ($this->img->Visible && !$this->img->ReadOnly && !$this->img->Upload->KeepFile) {
				$this->img->Upload->DbValue = $rsold['img']; // Get original value
				if ($this->img->Upload->FileName == "") {
					$rsnew['img'] = NULL;
				} else {
					$rsnew['img'] = $this->img->Upload->FileName;
				}
				$this->img->ImageWidth = 735; // Resize width
				$this->img->ImageHeight = 735; // Resize height
			}
			if ($this->img->Visible && !$this->img->Upload->KeepFile) {
				$OldFiles = ew_Empty($this->img->Upload->DbValue) ? array() : array($this->img->Upload->DbValue);
				if (!ew_Empty($this->img->Upload->FileName)) {
					$NewFiles = array($this->img->Upload->FileName);
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->img->Upload->Index < 0) ? $this->img->FldVar : substr($this->img->FldVar, 0, 1) . $this->img->Upload->Index . substr($this->img->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->img->TblVar) . $file)) {
								$OldFileFound = FALSE;
								$OldFileCount = count($OldFiles);
								for ($j = 0; $j < $OldFileCount; $j++) {
									$file1 = $OldFiles[$j];
									if ($file1 == $file) { // Old file found, no need to delete anymore
										unset($OldFiles[$j]);
										$OldFileFound = TRUE;
										break;
									}
								}
								if ($OldFileFound) // No need to check if file exists further
									continue;
								$file1 = ew_UploadFileNameEx($this->img->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->img->TblVar) . $file1) || file_exists($this->img->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->img->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->img->TblVar) . $file, ew_UploadTempPath($fldvar, $this->img->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->img->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->img->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->img->SetDbValueDef($rsnew, $this->img->Upload->FileName, NULL, $this->img->ReadOnly);
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->img->Visible && !$this->img->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->img->Upload->DbValue) ? array() : array($this->img->Upload->DbValue);
						if (!ew_Empty($this->img->Upload->FileName)) {
							$NewFiles = array($this->img->Upload->FileName);
							$NewFiles2 = array($rsnew['img']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->img->Upload->Index < 0) ? $this->img->FldVar : substr($this->img->FldVar, 0, 1) . $this->img->Upload->Index . substr($this->img->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->img->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->img->Upload->ResizeAndSaveToFile($this->img->ImageWidth, $this->img->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
											$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
											return FALSE;
										}
									}
								}
							}
						} else {
							$NewFiles = array();
						}
						$OldFileCount = count($OldFiles);
						for ($i = 0; $i < $OldFileCount; $i++) {
							if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
								@unlink($this->img->OldPhysicalUploadPath() . $OldFiles[$i]);
						}
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// img
		ew_CleanUploadTempPath($this->img, $this->img->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("productlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($product_edit)) $product_edit = new cproduct_edit();

// Page init
$product_edit->Page_Init();

// Page main
$product_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$product_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fproductedit = new ew_Form("fproductedit", "edit");

// Validate form
fproductedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_name_th");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $product->name_th->FldCaption(), $product->name_th->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description_th");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $product->description_th->FldCaption(), $product->description_th->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $product->name_en->FldCaption(), $product->name_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $product->description_en->FldCaption(), $product->description_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_size");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $product->size->FldCaption(), $product->size->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_size");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($product->size->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $product->type->FldCaption(), $product->type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_brand");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $product->brand->FldCaption(), $product->brand->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_img");
			elm = this.GetElements("fn_x" + infix + "_img");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $product->img->FldCaption(), $product->img->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fproductedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fproductedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fproductedit.Lists["x_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproductedit.Lists["x_type"].Options = <?php echo json_encode($product_edit->type->Options()) ?>;
fproductedit.Lists["x_brand"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproductedit.Lists["x_brand"].Options = <?php echo json_encode($product_edit->brand->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $product_edit->ShowPageHeader(); ?>
<?php
$product_edit->ShowMessage();
?>
<form name="fproductedit" id="fproductedit" class="<?php echo $product_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($product_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $product_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="product">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($product_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($product->product_id->Visible) { // product_id ?>
	<div id="r_product_id" class="form-group">
		<label id="elh_product_product_id" class="<?php echo $product_edit->LeftColumnClass ?>"><?php echo $product->product_id->FldCaption() ?></label>
		<div class="<?php echo $product_edit->RightColumnClass ?>"><div<?php echo $product->product_id->CellAttributes() ?>>
<span id="el_product_product_id">
<span<?php echo $product->product_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $product->product_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="product" data-field="x_product_id" name="x_product_id" id="x_product_id" value="<?php echo ew_HtmlEncode($product->product_id->CurrentValue) ?>">
<?php echo $product->product_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($product->name_th->Visible) { // name_th ?>
	<div id="r_name_th" class="form-group">
		<label id="elh_product_name_th" for="x_name_th" class="<?php echo $product_edit->LeftColumnClass ?>"><?php echo $product->name_th->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $product_edit->RightColumnClass ?>"><div<?php echo $product->name_th->CellAttributes() ?>>
<span id="el_product_name_th">
<input type="text" data-table="product" data-field="x_name_th" name="x_name_th" id="x_name_th" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($product->name_th->getPlaceHolder()) ?>" value="<?php echo $product->name_th->EditValue ?>"<?php echo $product->name_th->EditAttributes() ?>>
</span>
<?php echo $product->name_th->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($product->description_th->Visible) { // description_th ?>
	<div id="r_description_th" class="form-group">
		<label id="elh_product_description_th" for="x_description_th" class="<?php echo $product_edit->LeftColumnClass ?>"><?php echo $product->description_th->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $product_edit->RightColumnClass ?>"><div<?php echo $product->description_th->CellAttributes() ?>>
<span id="el_product_description_th">
<textarea data-table="product" data-field="x_description_th" name="x_description_th" id="x_description_th" cols="50" rows="10" placeholder="<?php echo ew_HtmlEncode($product->description_th->getPlaceHolder()) ?>"<?php echo $product->description_th->EditAttributes() ?>><?php echo $product->description_th->EditValue ?></textarea>
</span>
<?php echo $product->description_th->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($product->name_en->Visible) { // name_en ?>
	<div id="r_name_en" class="form-group">
		<label id="elh_product_name_en" for="x_name_en" class="<?php echo $product_edit->LeftColumnClass ?>"><?php echo $product->name_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $product_edit->RightColumnClass ?>"><div<?php echo $product->name_en->CellAttributes() ?>>
<span id="el_product_name_en">
<input type="text" data-table="product" data-field="x_name_en" name="x_name_en" id="x_name_en" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($product->name_en->getPlaceHolder()) ?>" value="<?php echo $product->name_en->EditValue ?>"<?php echo $product->name_en->EditAttributes() ?>>
</span>
<?php echo $product->name_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($product->description_en->Visible) { // description_en ?>
	<div id="r_description_en" class="form-group">
		<label id="elh_product_description_en" for="x_description_en" class="<?php echo $product_edit->LeftColumnClass ?>"><?php echo $product->description_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $product_edit->RightColumnClass ?>"><div<?php echo $product->description_en->CellAttributes() ?>>
<span id="el_product_description_en">
<textarea data-table="product" data-field="x_description_en" name="x_description_en" id="x_description_en" cols="50" rows="10" placeholder="<?php echo ew_HtmlEncode($product->description_en->getPlaceHolder()) ?>"<?php echo $product->description_en->EditAttributes() ?>><?php echo $product->description_en->EditValue ?></textarea>
</span>
<?php echo $product->description_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($product->size->Visible) { // size ?>
	<div id="r_size" class="form-group">
		<label id="elh_product_size" for="x_size" class="<?php echo $product_edit->LeftColumnClass ?>"><?php echo $product->size->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $product_edit->RightColumnClass ?>"><div<?php echo $product->size->CellAttributes() ?>>
<span id="el_product_size">
<input type="text" data-table="product" data-field="x_size" name="x_size" id="x_size" size="30" placeholder="<?php echo ew_HtmlEncode($product->size->getPlaceHolder()) ?>" value="<?php echo $product->size->EditValue ?>"<?php echo $product->size->EditAttributes() ?>>
</span>
<?php echo $product->size->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($product->type->Visible) { // type ?>
	<div id="r_type" class="form-group">
		<label id="elh_product_type" for="x_type" class="<?php echo $product_edit->LeftColumnClass ?>"><?php echo $product->type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $product_edit->RightColumnClass ?>"><div<?php echo $product->type->CellAttributes() ?>>
<span id="el_product_type">
<select data-table="product" data-field="x_type" data-value-separator="<?php echo $product->type->DisplayValueSeparatorAttribute() ?>" id="x_type" name="x_type"<?php echo $product->type->EditAttributes() ?>>
<?php echo $product->type->SelectOptionListHtml("x_type") ?>
</select>
</span>
<?php echo $product->type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($product->brand->Visible) { // brand ?>
	<div id="r_brand" class="form-group">
		<label id="elh_product_brand" for="x_brand" class="<?php echo $product_edit->LeftColumnClass ?>"><?php echo $product->brand->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $product_edit->RightColumnClass ?>"><div<?php echo $product->brand->CellAttributes() ?>>
<span id="el_product_brand">
<select data-table="product" data-field="x_brand" data-value-separator="<?php echo $product->brand->DisplayValueSeparatorAttribute() ?>" id="x_brand" name="x_brand"<?php echo $product->brand->EditAttributes() ?>>
<?php echo $product->brand->SelectOptionListHtml("x_brand") ?>
</select>
</span>
<?php echo $product->brand->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($product->img->Visible) { // img ?>
	<div id="r_img" class="form-group">
		<label id="elh_product_img" class="<?php echo $product_edit->LeftColumnClass ?>"><?php echo $product->img->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $product_edit->RightColumnClass ?>"><div<?php echo $product->img->CellAttributes() ?>>
<span id="el_product_img">
<div id="fd_x_img">
<span title="<?php echo $product->img->FldTitle() ? $product->img->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($product->img->ReadOnly || $product->img->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="product" data-field="x_img" name="x_img" id="x_img"<?php echo $product->img->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_img" id= "fn_x_img" value="<?php echo $product->img->Upload->FileName ?>">
<?php if (@$_POST["fa_x_img"] == "0") { ?>
<input type="hidden" name="fa_x_img" id= "fa_x_img" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_img" id= "fa_x_img" value="1">
<?php } ?>
<input type="hidden" name="fs_x_img" id= "fs_x_img" value="255">
<input type="hidden" name="fx_x_img" id= "fx_x_img" value="<?php echo $product->img->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_img" id= "fm_x_img" value="<?php echo $product->img->UploadMaxFileSize ?>">
</div>
<table id="ft_x_img" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $product->img->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$product_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $product_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $product_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fproductedit.Init();
</script>
<?php
$product_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$product_edit->Page_Terminate();
?>
