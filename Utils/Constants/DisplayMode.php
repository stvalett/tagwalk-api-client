<?php declare(strict_types=1);
/**
 * PHP version 7
 *
 * LICENSE: This source file is subject to copyright
 *
 * @package     Tagwalk\ApiClientBundle\Utils
 * @author      Florian Ajir <florian@tag-walk.com>
 * @copyright   2016-2018 TAGWALK
 * @license     proprietary
 */

namespace Tagwalk\ApiClientBundle\Utils\Constants;

/**
 * This class purpose is to list all available file display modes
 */
final class DisplayMode extends Constants
{
    /** @var string crop */
    const CROP = 'crop';

    /** @var string margin */
    const MARGIN = 'margin';

    /** @var array */
    const VALUES = [
        self::CROP,
        self::MARGIN,
    ];
}
