<?
defined('C5_EXECUTE') or die("Access Denied.");
class Concrete5_Controller_Frontend_Stylesheet extends Controller {

	public function page($cID, $cvID, $stylesheet) {
		$c = Page::getByID($cID);
		if (is_object($c) && !$c->isError()) {
			$cp = new Permissions($c);
			if ($cp->canViewPageVersions()) {
				$c->loadVersionObject($cvID);
				$pt = $c->getCollectionThemeObject();
				$values = $c->getCustomThemeStyles();
				$content = $pt->parseStyleSheet($stylesheet, $values);
				$response = new Response();
				$response->headers->set('Content-Type', 'text/css');
				$response->setContent($content);
				return $response;
			}
		}
	}

	public function layout($bID) {
		$b = Block::getByID($bID);
		if (is_object($b)) {
			$bc = $b->getController();
			if ($bc instanceof CoreAreaLayoutBlockController) {
				if (!is_object($bc->getAreaLayoutObject())) {
					die;
				}

				$arLayout = $bc->getAreaLayoutObject();

				$css = <<<EOL
	div.ccm-layout-column {
		float: left;
	}

	/* clearfix */

	div.ccm-layout-column-wrapper {*zoom:1;}
	div.ccm-layout-column-wrapper:before, div.ccm-layout-column-wrapper:after {display:table;content:"";line-height:0;}
	div.ccm-layout-column-wrapper:after {clear:both;}

EOL;
				$wrapper = 'ccm-layout-column-wrapper-' . $b->getBlockID();
				$columns = $arLayout->getAreaLayoutColumns();
				if (count($columns) > 0) {
					$margin = ($arLayout->getAreaLayoutSpacing() / 2);
					if ($arLayout->hasAreaLayoutCustomColumnWidths()) {
						foreach($columns as $col) {
							$arLayoutColumnID = $col->getAreaLayoutColumnID();
							$width = $col->getAreaLayoutColumnWidth();
							if ($width) {
								$width .= 'px';
							}

							$css .= "#{$wrapper} div#ccm-layout-column-{$arLayoutColumnID} { width: {$width}; }\n";
						}

					} else {
						$width = (100 / count($columns));
						$css .= <<<EOL

	#{$wrapper} div.ccm-layout-column {
		width: {$width}%;
	}
EOL;
					
					}

					$css .= <<<EOL

	#{$wrapper} div.ccm-layout-column-inner {
		margin-right: {$margin}px;
		margin-left: {$margin}px;
	}

	#{$wrapper} div.ccm-layout-column:first-child div.ccm-layout-column-inner {
		margin-left: 0px;
	}

	#{$wrapper} div.ccm-layout-column:last-child div.ccm-layout-column-inner  {
		margin-right: 0px;
	}
EOL;

					$response = new Response();
					$response->setContent($css);
					$response->headers->set('Content-Type', 'text/css');
					return $response;
				}
			}			
		}
	}
}

