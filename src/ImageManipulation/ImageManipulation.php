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

use ImageManipulation\ImageManipulationAbstract;

/**
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class ImageManipulation extends ImageManipulationAbstract
{
    /**
     * Current file for manipulation.
     *
     * @param SplFileInfo $file_info
     */
    protected $file_info;

    /**
     * Path used when generating thumbnails.
     *
     * @param string $location
     */
    private $location;

    /**
     * Sets the $file_info property.
     *
     * @param SplFileInfo $file_info
     *
     * @return bool
     */
    public function __construct(\SplFileInfo $file_info)
    {
        $this->file_info = $file_info;
    }

    /**
     * Gets the $file_info property.
     *
     * @return SplFileInfo for current file associated with this class.
     */
    public function getFileInfo()
    {
        return $this->file_info;
    }

    /**
     * Gets the $location property.
     *
     * @return string location path for new thumbnails.
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets the $location property used in generateThumbnail() function. Defaults
     * to same location as current $file_info.
     *
     * @param string $location
     *
     * @return bool
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

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
    protected function calculateDimensions($original_image, $max_width, $max_height)
    {
        $original_width = imagesx($original_image);
        $original_height = imagesy($original_image);

        $ratios['y_ratio'] = ($max_height / $original_height) * 100;
        $ratios['x_ratio'] = ($max_width / $original_width) * 100;

        $new_dimensions = array('x' => null, 'y' => null);
        foreach($ratios as $ratio_axis => $ratio) {
            $new_width = floor(($original_width / 100) * $ratio);
            $new_height = floor(($original_height / 100) * $ratio);
            if($new_height <= $max_height && $new_width <= $max_width) {
                $new_dimensions['x'] = $new_width;
                $new_dimensions['y'] = $new_height;
                break;
            }
        }

        return $new_dimensions;
    }

    /**
     * Gets all options when generating thumbnail combining passed options
     * which will overwrite default values.
     *
     * @param array $options
     *
     * @return array of $default_options
     */
    protected function getDefaultGenerateThumbnailOptions($options)
    {
        $default_options = array(
                'max_width' => 120,
                'max_height' => 120,
                'prefix' => 'thumbnail_',
                'suffix' => null,
                'jpeg_quality' => 75,
                'png_quality' => 3
            );

        foreach($options as $key => $value) {
            if(!isset($default_options[$key])) {
                throw new \Exception('Invalid option for generating thumbnails `'.$key.'`');
            }

            $default_options[$key] = $value;
        }

        return $default_options;
    }

    /**
     * Generates a virtual image to base new image from.
     *
     * @param int $width
     * @param int $height
     *
     * @return resource $virtual_image
     */
    protected function createVirtualImage($width, $height)
    {
        $virtual_image = imagecreatetruecolor($width, $height);
        return $virtual_image;
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
        imagecopyresampled($virtual_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, imagesx($original_image), imagesy($original_image));
        return $virtual_image;
    }

    /**
     * Generates new path with filename. Uses $location property if set.
     *
     * @param string $prefix new filename prefix
     * @param string $suffix new filename suffix
     *
     * @return string new path and filename
     */
    protected function getNewPath($prefix, $suffix)
    {
        $original_image_path = $this->file_info->getRealPath();
        if(!isset($this->location)) {
            $this->location = preg_replace('/(\/(?!.*\/)(?:.*))/', '', $original_image_path);
        }

        $extension = '.'.$this->file_info->getExtension();
        $filename_minus_extension = $this->file_info->getBasename($extension);
        $new_filename = $prefix.$filename_minus_extension.$suffix.$extension;
        return $this->location.'/'.$new_filename;
    }

    /**
     * Checks if thumbnail with specified dimensions already exists. Tolerance
     * when checking dimensions is +-30.
     *
     * @param string $path new filename path
     * @param string $resource_function function to call to create image resource
     * @param int $max_width
     * @param int $max_height
     *
     * @return bool
     */
    protected function checkForExisting($path, $resource_function, $max_width, $max_height)
    {
        $tolerance = 30;
        $neg_tolerance = -1 * abs($tolerance);
        if(file_exists($path)) {
            $thumbnail_resource = call_user_func_array($resource_function, array($path));
            $thumb_width = imagesy($thumbnail_resource);
            $thumb_height = imagesy($thumbnail_resource);
            $x_diff = $thumb_width - $max_width;
            $y_diff = $thumb_height - $max_height;
            $y_diff = $thumb_height - $max_height;
            if(($neg_tolerance <= $x_diff && $x_diff <= $tolerance) || ($neg_tolerance <= $y_diff && $y_diff <= $tolerance)) {
                return true;
            }

            unlink($path);
        }

        return false;
    }
}

?>