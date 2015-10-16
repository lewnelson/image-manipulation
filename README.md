# image-manipulation
A PHP class to manipulate images

This PHP tool is designed to generate thumbnails given the path to an image. Currently it supports png, gif and jpg images.

## Installation
Update composer.json
```
{
    "require": {
        "lewnelson/image-manipulation": "dev-master@dev"
    }
}
```

## Usage

To get started ensure you have loaded all classes into your script, then run the following:

```
use LewNelson\ImageManipulation\ImageManipulation;

$collection = ImageManipulationFactory::init($images);
```

Where $images is either a string of a path to an image or an array of paths to images. This will output either a string or array of ImageManipulation objects depending on the input. If you input an array the output will be an array with the same keys.

To generate a thumbnail you can then run the generateThumbnail() method on your ImageManipulation instances. By default without further configuration this will create a thumbnail in the same directory as the original image, prefixed by thumbnail_ with a max width of 120 and max height of 120.

The return from generating a thumbnail is an instance of the PHP class SplFileInfo.

The generateThumbnail() method accepts one parameter $options which is an array of options. This array consists of key => value pairs. The following options are available.
- max_width = integer, maximum thumbnail width (default = 120)
- max_height = integer, maximum thumbnail height (default = 120)
- prefix = string, prefix to new thumbnail name (default = thumbnail_)
- suffix = string, suffix to new thumbnail name (default = null)
- jpeg_quality = integer 0-100, compression quality of new jpeg thumbnails (default = 75)
- png_quality = integer 0-9, compression quality of new png thumbnails (default = 3)

In addition to setting these options you can also specify a location other than alongside the current image. This can be set via the setLocation((string)$location) method and passing an argument $location which is the new path.
