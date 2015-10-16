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

use ImageManipulation\ImageManipulationBase;
use ImageManipulation\ImageManipulationInterface;

/**
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class PngManipulation extends ImageManipulationBase implements ImageManipulationInterface
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
        $resource_function = 'imagecreatefrompng';
        if($this->checkForExisting($new_path, $resource_function, $options['max_width'], $options['max_height']) === true) {
            return new \SplFileInfo($new_path);
        }
        $source_image = imagecreatefrompng($this->file_info->getRealPath());
        $new_dimensions = $this->calculateDimensions($source_image, $options['max_width'], $options['max_height']);
        $virtual_image = $this->createVirtualImage($new_dimensions['x'], $new_dimensions['y']);
        $virtual_image = $this->createResampledImage($virtual_image, $source_image, $new_dimensions['x'], $new_dimensions['y']);
        imagepng($virtual_image, $new_path, $options['png_quality']);
        return new \SplFileInfo($new_path);
    }

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
    protected function createResampledImage($virtual_image, $original_image, $new_width, $new_height)
    {
        imagealphablending($virtual_image, false);
        imagesavealpha($virtual_image,true);
        $transparent = imagecolorallocatealpha($virtual_image, 255, 255, 255, 127);
        imagefilledrectangle($virtual_image, 0, 0, $new_width, $new_height, $transparent);
        imagecopyresampled($virtual_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, imagesx($original_image), imagesy($original_image));
        return $virtual_image;
    }
}

?>