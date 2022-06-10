<?php

namespace SVGTools;

use Exception;
use DOMDocument;

/**
 * Class for handling various tasks with an SVG file or script.
 * 
 * @author  Gareth Palmer   @evangeltheology
 * 
 * @version 1.0.0
 */

class SVG {

    /**
     * The DOMDocument object based on the SVG file.
     * 
     * @var DOMDocument  $xml
     * 
     * @access  private
     * @since   1.0.0
     */

    private DOMDocument $xml;

    /**
     * The SVG tag from the DOMDocument.
     * 
     * @var object  $svg_tag
     * 
     * @access  private
     * @since   1.0.0
     */

    private object $svg_tag;


    /**
     * Autoloading constructor.
     * 
     * @param   string  $svg    The base SVG file to work on.
     * 
     * @access  public
     * @since   1.0.0
     */


    public function __construct(

        /**
         * The Raw SVG image to be handled.
         * 
         * @var string  $svg
         * 
         * @access  protected
         * @since   1.0.0
         */

        protected string $svg
    ) {
        $this->xml = new DOMDocument;
        $this->xml->formatOutput = true;
        $this->xml->loadXML( $this->svg );

        // Basic SVG validation
        $svg_tag = $this->xml->getElementsByTagName( 'svg' )[0];
        if ( $svg_tag == 'NULL' || is_null( $svg_tag ) ) {
            throw new Exception( 'Invalid SVG image.' );
        }
        $this->svg_tag = $svg_tag;
    }


    /**
     * echo out the SVG file.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function echo(): static {
        echo $this->clean_up_svg( $this->xml->saveXML() );
        return $this;
    }


    /**
     * Return the SVG file.
     * 
     * @return  string
     * 
     * @access  public
     * @since   1.0.0
     */

    public function return(): string {
        return $this->clean_up_svg( $this->xml->saveXML() );
    }


    /**
     * Set a set attribute. Overwrites the attribute if it already exists.
     * 
     * @param   string  $attribute  The attribute to set.
     * @param   string  $value      The value for the attribute to set.
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_attribute( string $attribute, string $value ): static {
        $this->svg_tag->setAttribute( $attribute, $value );
        $this->xml->saveXML();
        return $this;
    }


    /**
     * Remove a set attribute.
     * 
     * @param   string  $attribute  The attribute to set.
     * 
     * @access  public
     * @since   1.0.0
     */

    public function remove_attribute( string $attribute ): static {
        $this->svg_tag->removeAttribute( $attribute );
        $this->xml->saveXML();
        return $this;
    }


    /**
     * Set the viewbox of the SVG object. This moves the element within the container.
     * 
     * @param   integer $minx       X offset from the center
     * @param   integer $miny       Y offset from the center
     * @param   integer $width      Width Offset
     * @param   integer $height     Height Offset
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_viewbox( int $minx, int $miny, int $width, int $height ): static {
        $this->set_attribute( 'viewBox', "{$minx} {$miny} {$width} {$height}" );
        return $this;
    }


    /**
     * Set the ID of the SVG tag on the element.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0 
     */

    public function set_id( string $id ): static {
        $this->set_attribute( 'id', $id );
        return $this;
    }


    /**
     * Set the width of the SVG image.
     * 
     * @param   integer $width  The new width
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_width( int $width ): static {
        $this->set_attribute( 'width', $width );
        return $this;
    }


    /**
     * Set the height of the SVG image.
     * 
     * @param   integer $height  The new height
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_height( int $height ): static {
        $this->set_attribute( 'height', $height );
        return $this;
    }


    /**
     * Set the dimensions of the SVG image.
     * 
     * @param   integer $width  The new width of the image.
     * @param   integer $height The new height of the image.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_size( int $width, int $height ): static {
        $this->set_width( $width );
        $this->set_height( $height );
        return $this;
    }


    /**
     * Add a class attribute to the <svg></svg> tag. Overwrites if already existing.
     * 
     * @param   string  $class  Class desired to be set.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_class( string $class ): static {
        $this->set_attribute( 'class', $class );
        return $this;
    }


    /**
     * Remove an entry from the classlist.
     * 
     * @param   string  $class  The entry to remove.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function remove_from_classlist( string $class ): static {
        $classes = array_flip( explode( ' ', $this->svg_tag->getAttribute( 'class' ) ) );
        unset( $classes[$class] );
        $final_class = implode( ' ', array_flip( $classes ) );
        if ( $final_class == '' ) {
            $this->remove_attribute( 'class' );
        } else {
            $this->set_attribute( 'class', $final_class );
        }
        return $this;
    }


    /**
     * Add an entry to the classlist.
     * 
     * Does not remove previous entries.
     * 
     * @param   string  $class  The entry to append.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function add_to_classlist( string $class ): static {
        if ( !$this->svg_tag->hasAttribute( 'class') ) {
            // Skip if class isn't already on the <svg></svg>
            $this->set_attribute( 'class', $class );
            return $this;
        }
        $origonal_class = $this->svg_tag->getAttribute( 'class' );
        $classes = array_flip( explode( ' ', $origonal_class ) );
        if ( isset( $classes[$class] ) ) {
            // Skip if already exists.
            return $this;
        }
        $this->set_attribute( 'class', $origonal_class . " {$class}" );
        return $this;
    }


    /**
     * Set the name attribute to the image. Will overwrite a previous entry.
     * 
     * @param   string  $name   Name attribute to add.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_name( string $name ): static {
        $this->set_attribute( 'name', $name );
        return $this;
    }


    /**
     * Add a 'data-attribute' element to the SVG.
     * 
     * @param   string  $key    The key to be added to 'data-'.
     * @param   string  $data   The data to add.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_dataset( string $key, string $data ): static {
        $this->set_attribute( "data-{$key}", $data );
        return $this;
    }


    /**
     * Set the fill attribute, will overwrite a previous fill attribute.
     * 
     * Essentially the background colour.
     * 
     * Besides colours, this can be set to "currentColor"
     * 
     * @param   string  $fill   The new colour to fill.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_fill( string $fill ): static {
        $this->set_attribute( 'fill', $fill );
        return $this;
    }


    /**
     * Set the stroke colour attribute, will overwrite a previous stroke attribute.
     * 
     * Essentially the line colour.
     * 
     * Besides colours, this can be set to "currentColor"
     * 
     * @param   string  $stroke The new colour to stroke
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_stroke( string $stroke ): static {
        $this->set_attribute( 'stroke', $stroke );
        return $this;
    }


    /**
     * Set a stroke attribute on the <svg></svg> tag. Will overwrite a previously set attribute.
     * 
     * @param   string  $key    The key to set. For example width => stroke-width
     * @param   string  $value  The value to set.
     * 
     * @return  static
     * 
     * @access  public
     * @since   1.0.0
     */

    public function set_stroke_attribute( string $key, string $value ): static {
        $this->set_attribute( "stroke-{$key}", $value );
        return $this;
    }


    /**
     * Remove the <?xml header from the SVG XML file.
     * 
     * @param   string  $svg
     * 
     * @return  string
     * 
     * @access  private
     * @since   1.0.0
     */

    private function clean_up_svg( string $svg ): string {
        return str_replace( "<?xml version=\"1.0\"?>\n", '', $svg );
    }

}