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
abstract class ImageManipulationAbstract
{
    /**
     * Sets the $location property used in generateThumbnail() function. Defaults
     * to same location as current $file_info.
     *
     * @param resource $original_image
     * @param string $max_width
     * @param string $max_height
     *
     * @return array of new dimensions keys ['x', 'y']
     */
    abstract protected function calculateDimensions($original_image, $max_width, $max_height);

    /**
     * Gets all options when generating thumbnail combining passed options
     * which will overwrite default values.
     *
     * @param array $options
     *
     * @return array of $default_options
     */
    abstract protected function getDefaultGenerateThumbnailOptions($options);

    /**
     * Creates a resampled image to specified dimensions
     *
     * @param resource $virtual_image the output image and new input
     * @param resource $original_image
     * @param int $new_width
     * @param int $new_height
     *
     * @return resource $virtual_image resampled
     */
    abstract protected function createResampledImage($virtual_image, $original_image, $new_width, $new_height);

    /**
     * Generates new path with filename. Uses $location property if set.
     *
     * @param string $prefix new filename prefix
     * @param string $suffix new filename suffix
     *
     * @return string new path and filename
     */
    abstract protected function getNewPath($prefix, $suffix);

    /**
     * Generates a virtual image to base new image from.
     *
     * @param int $width
     * @param int $height
     *
     * @return resource $virtual_image
     */
    abstract protected function createVirtualImage($width, $height);
}

?>