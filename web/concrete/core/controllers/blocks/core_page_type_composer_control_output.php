<?
	defined('C5_EXECUTE') or die("Access Denied.");
	class Concrete5_Controller_Block_CorePageTypeComposerControlOutput extends BlockController {

		protected $btCacheBlockRecord = true;
		protected $btTable = 'btCorePageTypeComposerControlOutput';
		protected $btIsInternal = true;		
		public function getBlockTypeDescription() {
			return t("Proxy block for blocks that need to be output through composer.");
		}
		
		public function getBlockTypeName() {
			return t("Composer Control (Core)");
		}

		public function export(SimpleXMLElement $blockNode) {			
			$outputControl = PageTypeComposerOutputControl::getByID($this->ptComposerOutputControlID);
			if (is_object($outputControl)) {
				$fsc = PageTypeComposerFormLayoutSetControl::getByID($outputControl->getPageTypeComposerFormLayoutSetControlID());
				if (is_object($fsc)) {
					$cnode = $blockNode->addChild('control');
					$cnode->addAttribute('output-control-id', ContentExporter::getPageTypeComposerOutputControlTemporaryID($fsc));
				}
			}
		}

		public function getImportData($blockNode, $page) {
			$args = array();
			$formLayoutSetControlID = ContentImporter::getPageTypeComposerFormLayoutSetControlFromTemporaryID((string) $blockNode->control['output-control-id']);
			$formLayoutSetControl = PageTypeComposerFormLayoutSetControl::getByID($formLayoutSetControlID);
			$b = $this->getBlockObject();
			$pt = PageTemplate::getByID($page->getPageTemplateID());
			$outputControl = PageTypeComposerOutputControl::getByPageTypeComposerFormLayoutSetControl($pt, $formLayoutSetControl);
			$args['ptComposerOutputControlID'] = $outputControl->getPageTypeComposerOutputControlID();			
			return $args;
		}
		
	}



