<?php

/**
 * Plugin to generate a 'file' element.
 *
 * @package Savant3
 * @subpackage Savant3_Plugin_Form
 * @author Paul M. Jones <pmjones@ciaweb.net>
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * @version $Id: Savant3_Plugin_formFile.php 6 2015-07-08 07:07:06Z PragmaMx $
 */

require_once 'Savant3_Plugin_form_element.php';

/**
 * Plugin to generate a 'file' element.
 *
 * @package Savant3
 * @subpackage Savant3_Plugin_Form
 * @author Paul M. Jones <pmjones@ciaweb.net>
 */

class Savant3_Plugin_formFile extends Savant3_Plugin_form_element {
    /**
     * Generates a 'file' element.
     *
     * @access public
     * @param string $ |array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     * @param mixed $value The element value.
     * @param array $attribs Attributes for the element tag.
     * @return string The element XHTML.
     */

    public function formFile($name, $value = null, $attribs = null)
    {
        $info = $this->getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        $xhtml = '';
        // build the element
        if ($disable) {
            // disabled
            $xhtml .= $this->Savant->formHidden($name, $value);
            $xhtml .= htmlspecialchars($value, ENT_COMPAT | ENT_HTML5, 'UTF-8', false);
        } else {
            // enabled
            $xhtml .= '<input type="file"';
            $xhtml .= ' name="' . htmlspecialchars($name, ENT_COMPAT | ENT_HTML5, 'UTF-8', false) . '"';
            $xhtml .= ' value="' . htmlspecialchars($value, ENT_COMPAT | ENT_HTML5, 'UTF-8', false) . '"';
            $xhtml .= $this->Savant->htmlAttribs($attribs);
            $xhtml .= ' />';
        }

        return $xhtml;
    }
}

?>