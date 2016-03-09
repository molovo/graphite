<?php

namespace Molovo\Graphite;

class Graphite
{
    /**
     * Color Constants.
     */
    const BLACK   = 0;
    const RED     = 1;
    const GREEN   = 2;
    const YELLOW  = 3;
    const BLUE    = 4;
    const MAGENTA = 5;
    const CYAN    = 6;
    const WHITE   = 7;
    const GRAY    = '8;5;242';

    /**
     * Style Constants.
     */
    const BOLD         = 1;
    const ITALIC       = 2;
    const UNDERLINE    = 4;
    const INVERSE      = 7;
    const STRIKETROUGH = 9;

    /**
     * ANSI Escape codes.
     */
    const ANSI_START = "\033[%d%s%sm";
    const ANSI_END   = "\033[0;m";

    /**
     * The current color of the output.
     *
     * @var int|null
     */
    private $color = null;

    /**
     * The current background color of the output.
     *
     * @var int|null
     */
    private $backgroundColor = null;

    /**
     * The current style of the output.
     *
     * @var int|null
     */
    private $style = null;

    /**
     * A global indent to be prepended to all strings.
     *
     * @var int
     */
    private $indent = 0;

    /**
     * Allow accessing methods as properties.
     *
     * @param string $key The method to call
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (!method_exists($this, $key)) {
            return;
        }

        return $this->{$key}();
    }

    /**
     * Render the string when the object is invoked.
     *
     * @param string $string A string to render
     *
     * @return string The rendered string
     */
    public function __invoke($string)
    {
        return $this->render($string);
    }

    /**
     * Output the string and reset styles.
     *
     * @param string $str The string to output
     *
     * @return self
     */
    public function render($str)
    {
        return $this->indent($this->encode($str))."\n";
    }

    /**
     * Add ANSI encoding to the string.
     *
     * @param string $str The string to encode
     *
     * @return string
     */
    public function encode($str)
    {
        $out                   = $this->start().$str.$this->end();
        $this->color           = null;
        $this->backgroundColor = null;
        $this->style           = null;

        return $out;
    }

    /**
     * Set the global indentation level.
     *
     * @param int $indent
     */
    public function setGlobalIndent($indent = 0)
    {
        $this->indent = (int) $indent;
    }

    /**
     * Set the output color.
     *
     * @param int    $color The color number
     * @param string $str   An optional string to output
     *
     * @return self
     */
    public function setColor($color, $str = null)
    {
        $this->color = $color;

        if ($str !== null) {
            return $this->encode($str);
        }

        return $this;
    }

    /**
     * Set the output background color.
     *
     * @param int    $color The color number
     * @param string $str   An optional string to output
     *
     * @return self
     */
    public function setBackgroundColor($color, $str = null)
    {
        $this->backgroundColor = $color;

        if ($str !== null) {
            return $this->encode($str);
        }

        return $this;
    }

    /**
     * Set the output style.
     *
     * @param int    $style The style number
     * @param string $str   An optional string to output
     */
    public function setStyle($style, $str = null)
    {
        $this->style = $style;

        if ($str !== null) {
            return $this->encode($str);
        }

        return $this;
    }

    /**
     * Set the color to black.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function black($str = null)
    {
        return $this->setColor(self::BLACK, $str);
    }

    /**
     * Set the background color to black.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function blackbg($str = null)
    {
        return $this->setBackgroundColor(self::BLACK, $str);
    }

    /**
     * Set the color to red.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function red($str = null)
    {
        return $this->setColor(self::RED, $str);
    }

    /**
     * Set the background color to red.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function redbg($str = null)
    {
        return $this->setBackgroundColor(self::RED, $str);
    }

    /**
     * Set the color to green.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function green($str = null)
    {
        return $this->setColor(self::GREEN, $str);
    }

    /**
     * Set the background color to green.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function greenbg($str = null)
    {
        return $this->setBackgroundColor(self::GREEN, $str);
    }

    /**
     * Set the color to yellow.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function yellow($str = null)
    {
        return $this->setColor(self::YELLOW, $str);
    }

    /**
     * Set the background color to yellow.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function yellowbg($str = null)
    {
        return $this->setBackgroundColor(self::YELLOW, $str);
    }

    /**
     * Set the color to blue.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function blue($str = null)
    {
        return $this->setColor(self::BLUE, $str);
    }

    /**
     * Set the background color to blue.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function bluebg($str = null)
    {
        return $this->setBackgroundColor(self::BLUE, $str);
    }

    /**
     * Set the color to magenta.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function magenta($str = null)
    {
        return $this->setColor(self::MAGENTA, $str);
    }

    /**
     * Set the background color to magenta.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function magentabg($str = null)
    {
        return $this->setBackgroundColor(self::MAGENTA, $str);
    }

    /**
     * Set the color to cyan.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function cyan($str = null)
    {
        return $this->setColor(self::CYAN, $str);
    }

    /**
     * Set the background color to cyan.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function cyanbg($str = null)
    {
        return $this->setBackgroundColor(self::CYAN, $str);
    }

    /**
     * Set the color to white.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function white($str = null)
    {
        return $this->setColor(self::WHITE, $str);
    }

    /**
     * Set the background color to white.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function whitebg($str = null)
    {
        return $this->setBackgroundColor(self::WHITE, $str);
    }

    /**
     * Set the color to gray.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function gray($str = null)
    {
        return $this->setColor(self::GRAY, $str);
    }

    /**
     * Set the background color to gray.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function graybg($str = null)
    {
        return $this->setBackgroundColor(self::GRAY, $str);
    }

    /**
     * Set the style to bold.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function bold($str = null)
    {
        return $this->setStyle(self::BOLD, $str);
    }

    /**
     * Set the style to italic.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function italic($str = null)
    {
        return $this->setStyle(self::ITALIC, $str);
    }

    /**
     * Set the style to underline.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function underline($str = null)
    {
        return $this->setStyle(self::UNDERLINE, $str);
    }

    /**
     * Set the style to inverse.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function inverse($str = null)
    {
        return $this->setStyle(self::INVERSE, $str);
    }

    /**
     * Set the style to strikethrough.
     *
     * @param string $str An optional string to output
     *
     * @return self
     */
    public function strikethrough($str = null)
    {
        return $this->setStyle(self::STRIKETROUGH, $str);
    }

    /**
     * Strip ANSI escape codes from a string.
     *
     * @param string $str The string to strip
     *
     * @return string The stripped string
     */
    public function strip($str)
    {
        return preg_replace("/(?:\\033\[\d(;\d*)*m)/", '', $str);
    }

    /**
     * Create a string by repeating a character to the specified length.
     *
     * @param string $char The character to repeat
     * @param int    $len  The length to repeat to
     *
     * @return string
     */
    public function repeat($char, $len)
    {
        $str  = '';
        $size = 0;
        while ($size++ < $len) {
            $str .= $char;
        }

        return $str;
    }

    /**
     * Indent the output prior to rendering.
     *
     * @param string $string The string to indent
     *
     * @return string The indented output
     */
    private function indent($string)
    {
        $lines = explode("\n", $string);

        foreach ($lines as $i => $line) {
            $lines[$i] = $this->repeat(' ', $this->indent).$line;
        }

        return implode("\n", $lines);
    }

    /**
     * Get the ANSI escape code to open the string.
     *
     * @return string
     */
    private function start()
    {
        $color = null;
        if ($this->color !== null) {
            $color = ';3'.$this->color;
        }

        $background = null;
        if ($this->backgroundColor !== null) {
            $background = ';4'.$this->backgroundColor;
        }

        return sprintf(self::ANSI_START, (int) $this->style, $color, $background);
    }

    /**
     * Get the ANSI escape code to end the string.
     *
     * @return string
     */
    private function end()
    {
        return self::ANSI_END;
    }
}
