<?php

/**
 * This file is part of the LewisNelson/ImageManipulation package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ImageManipulation\Types;

use ImageManipulation\ImageManipulation;
use ImageManipulation\ImageManipulationInterface;

/**
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class JpegManipulation extends ImageManipulation implements ImageManipulationInterface
{
    /**
     * Generates a thumbnail for the image based on optional array of options.
     *
     * @param array $options
     *
     * @return SplFileInfo instance for new thumbnail
     */
    public function generateThumbnail($options = array())
    {
        $options = $this->getDefaultGenerateThumbnailOptions($options);
        $new_path = $this->getNewPath($options['prefix'], $options['suffix']);
        $resource_function = 'imagecreatefromjpeg';
        if($this->checkForExisting($new_path, $resource_function, $options['max_width'], $options['max_height']) === true) {
            return new \SplFileInfo($new_path);
        }
        $source_image = imagecreatefromjpeg($this->file_info->getRealPath());
        $new_dimensions = $this->calculateDimensions($source_image, $options['max_width'], $options['max_height']);
        $virtual_image = $this->createVirtualImage($new_dimensions['x'], $new_dimensions['y']);
        $virtual_image = $this->createResampledImage($virtual_image, $source_image, $new_dimensions['x'], $new_dimensions['y']);
        imagejpeg($virtual_image, $new_path, $options['jpeg_quality']);
        return new \SplFileInfo($new_path);
    }
}

?>