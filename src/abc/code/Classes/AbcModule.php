<?php
namespace Azt3k\SS\Classes;
use SilverStripe\Admin\LeftAndMain;
use Azt3k\SS\Classes\LeftAndMainHelper;
use SilverStripe\View\Requirements;
use \Exception;

class AbcModule {
	public static function load($name) {
		switch ($name) {

			case 'jquery':

				// loaders / blockers
				Requirements::block(THIRDPARTY_DIR."/jquery/jquery.js");
				Requirements::javascript(ABC_PATH."/javascript/library/jQuery/jquery-1.10.2.min.js");
				LeftAndMainHelper::require_unblock(THIRDPARTY_DIR."/jquery/jquery.js");
				LeftAndMainHelper::require_block(ABC_PATH."/javascript/library/jQuery/jquery-1.10.2.min.js");
				LeftAndMain::require_javascript(THIRDPARTY_DIR."/jquery/jquery.js");
				break;

			case 'jquery-mobile':

				// dependencies
				self::load('jquery');

				// loaders / blockers
				Requirements::javascript(ABC_PATH.'/javascript/library/jQuery/mobile/jquery.mobile-1.3.2.min.js');
				LeftAndMainHelper::require_block(ABC_PATH.'/javascript/library/jQuery/mobile/jquery.mobile-1.3.2.min.js');
				break;

			case 'lean-modal':

				// dependencies
				self::load('jquery');

				// loaders / blockers
				Requirements::javascript(ABC_PATH."/javascript/library/jQuery/lean-modal/jquery.lean-modal.min.js");
				Requirements::css(ABC_PATH."/javascript/library/jQuery/lean-modal/jquery.lean-modal.css");
				LeftAndMainHelper::require_block(ABC_PATH."/javascript/library/jQuery/lean-modal/jquery.lean-modal.min.js");
				LeftAndMainHelper::require_block(ABC_PATH."/javascript/library/jQuery/lean-modal/jquery.lean-modal.css");
				break;

			case 'avgrund':

				// dependencies
				self::load('jquery');

				// loaders / blockers
				Requirements::javascript(ABC_PATH."/javascript/library/jQuery/avgrund/jquery.avgrund.js");
				Requirements::css(ABC_PATH."/javascript/library/jQuery/avgrund/avgrund.css");
				LeftAndMainHelper::require_block(ABC_PATH."/javascript/library/jQuery/avgrund/jquery.avgrund.js");
				LeftAndMainHelper::require_block(ABC_PATH."/javascript/library/jQuery/avgrund/argvund.css");
				break;

			case 'bootstrap':

				// dependencies
				self::load('jquery');

				// loaders / blockers
				Requirements::javascript(ABC_PATH.'/lib/bootstrap/css/bootstrap.min.css');
				LeftAndMainHelper::require_block(ABC_PATH.'/lib/bootstrap/js/bootstrap.min.js');
				break;

			case 'slidatron':

				// dependencies
				self::load('jquery');

				// loaders / blockers
				Requirements::javascript(ABC_PATH.'/javascript/library/jQuery/event.drag/jquery.event.drag.js');
				Requirements::javascript(ABC_PATH.'/javascript/library/jQuery/event.drag/jquery.event.drag.live.js');
				Requirements::javascript(ABC_PATH.'/javascript/library/jquery.drag.touch.js');
				Requirements::javascript(ABC_PATH.'/javascript/library/jQuery/slidatron/jquery.slidatron.js');
				// Requirements::css(ABC_PATH.'/javascript/library/jQuery/slidatron/slidatron.css');
				LeftAndMainHelper::require_block(ABC_PATH.'/javascript/library/jQuery/event.drag/jquery.event.drag.js');
				LeftAndMainHelper::require_block(ABC_PATH.'/javascript/library/jQuery/event.drag/jquery.event.drag.live.js');
				LeftAndMainHelper::require_block(ABC_PATH.'/javascript/library/jquery.drag.touch.js');
				LeftAndMainHelper::require_block(ABC_PATH.'/javascript/library/jQuery/slidatron/jquery.slidatron.js');
				break;

			case 'nivo-slider':

				// dependencies
				self::load('jquery');

				// loaders / blockers
				Requirements::javascript(ABC_PATH.'/javascript/library/jQuery/nivo-slider/jquery.nivo.slider.pack.js');
				Requirements::css(ABC_PATH.'/javascript/library/jQuery/nivo-slider/nivo-slider.css');
				Requirements::css(ABC_PATH.'/javascript/library/jQuery/nivo-slider/themes/default/default.css');
				LeftAndMainHelper::require_block(ABC_PATH.'/javascript/library/jQuery/nivo-slider/jquery.nivo.slider.pack.js');
				LeftAndMainHelper::require_block(ABC_PATH.'/javascript/library/jQuery/nivo-slider/nivo-slider.css');
				LeftAndMainHelper::require_block(ABC_PATH.'/javascript/library/jQuery/nivo-slider/themes/default/default.css');
				break;

			default:
				throw new Exception('Invalid module requested; currently available modules are: jquery, jquery-mobile, bootstrap, avgrund, slidatron, nivo-slider');
				break;
		}
	}

	public static function combine() {

	}
}