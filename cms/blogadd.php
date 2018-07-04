<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "bloginfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$blog_add = NULL; // Initialize page object first

class cblog_add extends cblog {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{5E0F01B1-4565-4ABF-9ED4-51691E2A8222}';

	// Table name
	var $TableName = 'blog';

	// Page object name
	var $PageObjName = 'blog_add';

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

		// Table object (blog)
		if (!isset($GLOBALS["blog"]) || get_class($GLOBALS["blog"]) == "cblog") {
			$GLOBALS["blog"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["blog"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'blog', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("bloglist.php"));
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
		$this->name->SetVisibility();
		$this->description_head->SetVisibility();
		$this->description->SetVisibility();
		$this->date->SetVisibility();
		$this->date_create->SetVisibility();
		$this->image_upload->SetVisibility();
		$this->image_upload_head->SetVisibility();

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
		global $EW_EXPORT, $blog;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($blog);
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
					if ($pageName == "blogview.php")
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["blog_id"] != "") {
				$this->blog_id->setQueryStringValue($_GET["blog_id"]);
				$this->setKey("blog_id", $this->blog_id->CurrentValue); // Set up key
			} else {
				$this->setKey("blog_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("bloglist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "bloglist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "blogview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->image_upload->Upload->Index = $objForm->Index;
		$this->image_upload->Upload->UploadFile();
		$this->image_upload->CurrentValue = $this->image_upload->Upload->FileName;
		$this->image_upload_head->Upload->Index = $objForm->Index;
		$this->image_upload_head->Upload->UploadFile();
		$this->image_upload_head->CurrentValue = $this->image_upload_head->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->blog_id->CurrentValue = NULL;
		$this->blog_id->OldValue = $this->blog_id->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->description_head->CurrentValue = NULL;
		$this->description_head->OldValue = $this->description_head->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->date->CurrentValue = NULL;
		$this->date->OldValue = $this->date->CurrentValue;
		$this->date_create->CurrentValue = NULL;
		$this->date_create->OldValue = $this->date_create->CurrentValue;
		$this->image_upload->Upload->DbValue = NULL;
		$this->image_upload->OldValue = $this->image_upload->Upload->DbValue;
		$this->image_upload->CurrentValue = NULL; // Clear file related field
		$this->image_upload_head->Upload->DbValue = NULL;
		$this->image_upload_head->OldValue = $this->image_upload_head->Upload->DbValue;
		$this->image_upload_head->CurrentValue = NULL; // Clear file related field
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->description_head->FldIsDetailKey) {
			$this->description_head->setFormValue($objForm->GetValue("x_description_head"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->date->FldIsDetailKey) {
			$this->date->setFormValue($objForm->GetValue("x_date"));
			$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		}
		if (!$this->date_create->FldIsDetailKey) {
			$this->date_create->setFormValue($objForm->GetValue("x_date_create"));
			$this->date_create->CurrentValue = ew_UnFormatDateTime($this->date_create->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->description_head->CurrentValue = $this->description_head->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		$this->date_create->CurrentValue = $this->date_create->FormValue;
		$this->date_create->CurrentValue = ew_UnFormatDateTime($this->date_create->CurrentValue, 0);
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
		$this->blog_id->setDbValue($row['blog_id']);
		$this->name->setDbValue($row['name']);
		$this->description_head->setDbValue($row['description_head']);
		$this->description->setDbValue($row['description']);
		$this->date->setDbValue($row['date']);
		$this->date_create->setDbValue($row['date_create']);
		$this->image_upload->Upload->DbValue = $row['image_upload'];
		$this->image_upload->setDbValue($this->image_upload->Upload->DbValue);
		$this->image_upload_head->Upload->DbValue = $row['image_upload_head'];
		$this->image_upload_head->setDbValue($this->image_upload_head->Upload->DbValue);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['blog_id'] = $this->blog_id->CurrentValue;
		$row['name'] = $this->name->CurrentValue;
		$row['description_head'] = $this->description_head->CurrentValue;
		$row['description'] = $this->description->CurrentValue;
		$row['date'] = $this->date->CurrentValue;
		$row['date_create'] = $this->date_create->CurrentValue;
		$row['image_upload'] = $this->image_upload->Upload->DbValue;
		$row['image_upload_head'] = $this->image_upload_head->Upload->DbValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->blog_id->DbValue = $row['blog_id'];
		$this->name->DbValue = $row['name'];
		$this->description_head->DbValue = $row['description_head'];
		$this->description->DbValue = $row['description'];
		$this->date->DbValue = $row['date'];
		$this->date_create->DbValue = $row['date_create'];
		$this->image_upload->Upload->DbValue = $row['image_upload'];
		$this->image_upload_head->Upload->DbValue = $row['image_upload_head'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("blog_id")) <> "")
			$this->blog_id->CurrentValue = $this->getKey("blog_id"); // blog_id
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
		// blog_id
		// name
		// description_head
		// description
		// date
		// date_create
		// image_upload
		// image_upload_head

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// description_head
			$this->description_head->EditAttrs["class"] = "form-control";
			$this->description_head->EditCustomAttributes = 255;
			$this->description_head->EditValue = ew_HtmlEncode($this->description_head->CurrentValue);
			$this->description_head->PlaceHolder = ew_RemoveHtml($this->description_head->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = 1000;
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// date
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date->CurrentValue, 8));
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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->image_upload);

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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->image_upload_head);

			// Add refer script
			// name

			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// description_head
			$this->description_head->LinkCustomAttributes = "";
			$this->description_head->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";

			// date_create
			$this->date_create->LinkCustomAttributes = "";
			$this->date_create->HrefValue = "";

			// image_upload
			$this->image_upload->LinkCustomAttributes = "";
			$this->image_upload->HrefValue = "";
			$this->image_upload->HrefValue2 = $this->image_upload->UploadPath . $this->image_upload->Upload->DbValue;

			// image_upload_head
			$this->image_upload_head->LinkCustomAttributes = "";
			$this->image_upload_head->HrefValue = "";
			$this->image_upload_head->HrefValue2 = $this->image_upload_head->UploadPath . $this->image_upload_head->Upload->DbValue;
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
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!$this->description_head->FldIsDetailKey && !is_null($this->description_head->FormValue) && $this->description_head->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description_head->FldCaption(), $this->description_head->ReqErrMsg));
		}
		if (!$this->description->FldIsDetailKey && !is_null($this->description->FormValue) && $this->description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->description->FldCaption(), $this->description->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->date->FormValue)) {
			ew_AddMessage($gsFormError, $this->date->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// description_head
		$this->description_head->SetDbValueDef($rsnew, $this->description_head->CurrentValue, NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// date
		$this->date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date->CurrentValue, 0), NULL, FALSE);

		// date_create
		$this->date_create->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['date_create'] = &$this->date_create->DbValue;

		// image_upload
		if ($this->image_upload->Visible && !$this->image_upload->Upload->KeepFile) {
			$this->image_upload->Upload->DbValue = ""; // No need to delete old file
			if ($this->image_upload->Upload->FileName == "") {
				$rsnew['image_upload'] = NULL;
			} else {
				$rsnew['image_upload'] = $this->image_upload->Upload->FileName;
			}
		}

		// image_upload_head
		if ($this->image_upload_head->Visible && !$this->image_upload_head->Upload->KeepFile) {
			$this->image_upload_head->Upload->DbValue = ""; // No need to delete old file
			if ($this->image_upload_head->Upload->FileName == "") {
				$rsnew['image_upload_head'] = NULL;
			} else {
				$rsnew['image_upload_head'] = $this->image_upload_head->Upload->FileName;
			}
		}
		if ($this->image_upload->Visible && !$this->image_upload->Upload->KeepFile) {
			$OldFiles = ew_Empty($this->image_upload->Upload->DbValue) ? array() : explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->image_upload->Upload->DbValue));
			if (!ew_Empty($this->image_upload->Upload->FileName)) {
				$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->image_upload->Upload->FileName));
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->image_upload->Upload->Index < 0) ? $this->image_upload->FldVar : substr($this->image_upload->FldVar, 0, 1) . $this->image_upload->Upload->Index . substr($this->image_upload->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->image_upload->TblVar) . $file)) {
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
							$file1 = ew_UploadFileNameEx($this->image_upload->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->image_upload->TblVar) . $file1) || file_exists($this->image_upload->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->image_upload->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->image_upload->TblVar) . $file, ew_UploadTempPath($fldvar, $this->image_upload->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->image_upload->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->image_upload->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->image_upload->SetDbValueDef($rsnew, $this->image_upload->Upload->FileName, NULL, FALSE);
			}
		}
		if ($this->image_upload_head->Visible && !$this->image_upload_head->Upload->KeepFile) {
			$OldFiles = ew_Empty($this->image_upload_head->Upload->DbValue) ? array() : array($this->image_upload_head->Upload->DbValue);
			if (!ew_Empty($this->image_upload_head->Upload->FileName)) {
				$NewFiles = array($this->image_upload_head->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->image_upload_head->Upload->Index < 0) ? $this->image_upload_head->FldVar : substr($this->image_upload_head->FldVar, 0, 1) . $this->image_upload_head->Upload->Index . substr($this->image_upload_head->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->image_upload_head->TblVar) . $file)) {
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
							$file1 = ew_UploadFileNameEx($this->image_upload_head->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->image_upload_head->TblVar) . $file1) || file_exists($this->image_upload_head->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->image_upload_head->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->image_upload_head->TblVar) . $file, ew_UploadTempPath($fldvar, $this->image_upload_head->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->image_upload_head->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->image_upload_head->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->image_upload_head->SetDbValueDef($rsnew, $this->image_upload_head->Upload->FileName, NULL, FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->image_upload->Visible && !$this->image_upload->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->image_upload->Upload->DbValue) ? array() : explode(EW_MULTIPLE_UPLOAD_SEPARATOR, strval($this->image_upload->Upload->DbValue));
					if (!ew_Empty($this->image_upload->Upload->FileName)) {
						$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->image_upload->Upload->FileName);
						$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['image_upload']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->image_upload->Upload->Index < 0) ? $this->image_upload->FldVar : substr($this->image_upload->FldVar, 0, 1) . $this->image_upload->Upload->Index . substr($this->image_upload->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->image_upload->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->image_upload->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
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
							@unlink($this->image_upload->OldPhysicalUploadPath() . $OldFiles[$i]);
					}
				}
				if ($this->image_upload_head->Visible && !$this->image_upload_head->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->image_upload_head->Upload->DbValue) ? array() : array($this->image_upload_head->Upload->DbValue);
					if (!ew_Empty($this->image_upload_head->Upload->FileName)) {
						$NewFiles = array($this->image_upload_head->Upload->FileName);
						$NewFiles2 = array($rsnew['image_upload_head']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->image_upload_head->Upload->Index < 0) ? $this->image_upload_head->FldVar : substr($this->image_upload_head->FldVar, 0, 1) . $this->image_upload_head->Upload->Index . substr($this->image_upload_head->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->image_upload_head->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->image_upload_head->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
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
							@unlink($this->image_upload_head->OldPhysicalUploadPath() . $OldFiles[$i]);
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
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// image_upload
		ew_CleanUploadTempPath($this->image_upload, $this->image_upload->Upload->Index);

		// image_upload_head
		ew_CleanUploadTempPath($this->image_upload_head, $this->image_upload_head->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("bloglist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($blog_add)) $blog_add = new cblog_add();

// Page init
$blog_add->Page_Init();

// Page main
$blog_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$blog_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fblogadd = new ew_Form("fblogadd", "add");

// Validate form
fblogadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $blog->name->FldCaption(), $blog->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description_head");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $blog->description_head->FldCaption(), $blog->description_head->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $blog->description->FldCaption(), $blog->description->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($blog->date->FldErrMsg()) ?>");

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
fblogadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fblogadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $blog_add->ShowPageHeader(); ?>
<?php
$blog_add->ShowMessage();
?>
<form name="fblogadd" id="fblogadd" class="<?php echo $blog_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($blog_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $blog_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="blog">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($blog_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($blog->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_blog_name" for="x_name" class="<?php echo $blog_add->LeftColumnClass ?>"><?php echo $blog->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $blog_add->RightColumnClass ?>"><div<?php echo $blog->name->CellAttributes() ?>>
<span id="el_blog_name">
<input type="text" data-table="blog" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($blog->name->getPlaceHolder()) ?>" value="<?php echo $blog->name->EditValue ?>"<?php echo $blog->name->EditAttributes() ?>>
</span>
<?php echo $blog->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->description_head->Visible) { // description_head ?>
	<div id="r_description_head" class="form-group">
		<label id="elh_blog_description_head" for="x_description_head" class="<?php echo $blog_add->LeftColumnClass ?>"><?php echo $blog->description_head->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $blog_add->RightColumnClass ?>"><div<?php echo $blog->description_head->CellAttributes() ?>>
<span id="el_blog_description_head">
<textarea data-table="blog" data-field="x_description_head" name="x_description_head" id="x_description_head" cols="100" rows="10" placeholder="<?php echo ew_HtmlEncode($blog->description_head->getPlaceHolder()) ?>"<?php echo $blog->description_head->EditAttributes() ?>><?php echo $blog->description_head->EditValue ?></textarea>
</span>
<?php echo $blog->description_head->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_blog_description" class="<?php echo $blog_add->LeftColumnClass ?>"><?php echo $blog->description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $blog_add->RightColumnClass ?>"><div<?php echo $blog->description->CellAttributes() ?>>
<span id="el_blog_description">
<?php ew_AppendClass($blog->description->EditAttrs["class"], "editor"); ?>
<textarea data-table="blog" data-field="x_description" name="x_description" id="x_description" cols="100" rows="20" placeholder="<?php echo ew_HtmlEncode($blog->description->getPlaceHolder()) ?>"<?php echo $blog->description->EditAttributes() ?>><?php echo $blog->description->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fblogadd", "x_description", 100, 20, <?php echo ($blog->description->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $blog->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->date->Visible) { // date ?>
	<div id="r_date" class="form-group">
		<label id="elh_blog_date" for="x_date" class="<?php echo $blog_add->LeftColumnClass ?>"><?php echo $blog->date->FldCaption() ?></label>
		<div class="<?php echo $blog_add->RightColumnClass ?>"><div<?php echo $blog->date->CellAttributes() ?>>
<span id="el_blog_date">
<input type="text" data-table="blog" data-field="x_date" name="x_date" id="x_date" placeholder="<?php echo ew_HtmlEncode($blog->date->getPlaceHolder()) ?>" value="<?php echo $blog->date->EditValue ?>"<?php echo $blog->date->EditAttributes() ?>>
<?php if (!$blog->date->ReadOnly && !$blog->date->Disabled && !isset($blog->date->EditAttrs["readonly"]) && !isset($blog->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fblogadd", "x_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $blog->date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->image_upload->Visible) { // image_upload ?>
	<div id="r_image_upload" class="form-group">
		<label id="elh_blog_image_upload" class="<?php echo $blog_add->LeftColumnClass ?>"><?php echo $blog->image_upload->FldCaption() ?></label>
		<div class="<?php echo $blog_add->RightColumnClass ?>"><div<?php echo $blog->image_upload->CellAttributes() ?>>
<span id="el_blog_image_upload">
<div id="fd_x_image_upload">
<span title="<?php echo $blog->image_upload->FldTitle() ? $blog->image_upload->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($blog->image_upload->ReadOnly || $blog->image_upload->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="blog" data-field="x_image_upload" name="x_image_upload" id="x_image_upload" multiple="multiple"<?php echo $blog->image_upload->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_image_upload" id= "fn_x_image_upload" value="<?php echo $blog->image_upload->Upload->FileName ?>">
<input type="hidden" name="fa_x_image_upload" id= "fa_x_image_upload" value="0">
<input type="hidden" name="fs_x_image_upload" id= "fs_x_image_upload" value="255">
<input type="hidden" name="fx_x_image_upload" id= "fx_x_image_upload" value="<?php echo $blog->image_upload->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image_upload" id= "fm_x_image_upload" value="<?php echo $blog->image_upload->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_image_upload" id= "fc_x_image_upload" value="<?php echo $blog->image_upload->UploadMaxFileCount ?>">
</div>
<table id="ft_x_image_upload" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $blog->image_upload->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->image_upload_head->Visible) { // image_upload_head ?>
	<div id="r_image_upload_head" class="form-group">
		<label id="elh_blog_image_upload_head" class="<?php echo $blog_add->LeftColumnClass ?>"><?php echo $blog->image_upload_head->FldCaption() ?></label>
		<div class="<?php echo $blog_add->RightColumnClass ?>"><div<?php echo $blog->image_upload_head->CellAttributes() ?>>
<span id="el_blog_image_upload_head">
<div id="fd_x_image_upload_head">
<span title="<?php echo $blog->image_upload_head->FldTitle() ? $blog->image_upload_head->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($blog->image_upload_head->ReadOnly || $blog->image_upload_head->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="blog" data-field="x_image_upload_head" name="x_image_upload_head" id="x_image_upload_head"<?php echo $blog->image_upload_head->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_image_upload_head" id= "fn_x_image_upload_head" value="<?php echo $blog->image_upload_head->Upload->FileName ?>">
<input type="hidden" name="fa_x_image_upload_head" id= "fa_x_image_upload_head" value="0">
<input type="hidden" name="fs_x_image_upload_head" id= "fs_x_image_upload_head" value="255">
<input type="hidden" name="fx_x_image_upload_head" id= "fx_x_image_upload_head" value="<?php echo $blog->image_upload_head->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image_upload_head" id= "fm_x_image_upload_head" value="<?php echo $blog->image_upload_head->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image_upload_head" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $blog->image_upload_head->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$blog_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $blog_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $blog_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fblogadd.Init();
</script>
<?php
$blog_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$blog_add->Page_Terminate();
?>
