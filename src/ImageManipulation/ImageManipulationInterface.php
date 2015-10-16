<?php

/**
 * This file is part of the LewisNelson/ImageManipulation package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ImageManipulation;

/**
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
interface ImageManipulationInterface
{
    /**
     * Sets the $file_info property.
     *
     * @param SplFileInfo $file_info
     *
     * @return bool
     */
    public function __construct(\SplFileInfo $file_info);

    /**
     * Generates a thumbnail for the image based on optional array of options.
     *
     * @param array $options
     *
     * @return SplFileInfo instance for new thumbnail
     */
    public function generateThumbnail($options);

    /**
     * Gets the $file_info property.
     *
     * @return SplFileInfo for current file associated with this class.
     */
    public function getFileInfo();

    /**
     * Gets the $location property.
     *
     * @return string location path for new thumbnails.
     */
    public function getLocation();

    /**
     * Sets the $location property used in generateThumbnail() function. Defaults
     * to same location as current $file_info.
     *
     * @param string $location
     *
     * @return bool
     */
    public function setLocation($location);
}

?>