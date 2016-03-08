<?php

namespace Molovo\Graphite;

class Box
{
    /**
     *
     */
    const SINGLE = [
        'bottomLeft'  => '└',
        'bottomRight' => '┘',
        'horizontal'  => '─',
        'topLeft'     => '┌',
        'topRight'    => '┐',
        'vertical'    => '│',
    ];

    /**
     *
     */
    const DOUBLE = [
        'bottomLeft'  => '╚',
        'bottomRight' => '╝',
        'horizontal'  => '═',
        'topLeft'     => '╔',
        'topRight'    => '╗',
        'vertical'    => '║',
    ];

    /**
     *
     */
    const ROUNDED = [
        'bottomLeft'  => '╰',
        'bottomRight' => '╯',
        'horizontal'  => '─',
        'topLeft'     => '╭',
        'topRight'    => '╮',
        'vertical'    => '│',
    ];

    /**
     *
     */
    const SINGLE_DOUBLE = [
        'bottomLeft'  => '╙',
        'bottomRight' => '╜',
        'horizontal'  => '─',
        'topLeft'     => '╓',
        'topRight'    => '╖',
        'vertical'    => '║',
    ];

    /**
     *
     */
    const DOUBLE_SINGLE = [
        'bottomLeft'  => '╘',
        'bottomRight' => '╛',
        'horizontal'  => '═',
        'topLeft'     => '╒',
        'topRight'    => '╕',
        'vertical'    => '│',
    ];

    /**
     *
     */
    const CLASSIC = [
        'bottomLeft'  => '+',
        'bottomRight' => '+',
        'horizontal'  => '-',
        'topLeft'     => '+',
        'topRight'    => '+',
        'vertical'    => '|',
    ];

    /**
     *
     */
    const NO_BORDER = [
        'bottomLeft'  => '',
        'bottomRight' => '',
        'horizontal'  => '',
        'topLeft'     => '',
        'topRight'    => '',
        'vertical'    => '',
    ];

    /**
     * The default box styles.
     *
     * @var array
     */
    private $styles = [
        'borderColor' => Graphite::WHITE,
        'borderStyle' => self::SINGLE,
        'marginX'     => 0,
        'marginY'     => 0,
        'paddingX'    => 0,
        'paddingY'    => 0,
    ];

    /**
     * A title for the box.
     *
     * @var string
     */
    private $title = null;

    /**
     * A graphite object for styling strings.
     *
     * @var Graphite|null
     */
    private $graphite = null;

    /**
     * Create the box object.
     *
     * @param string|array $content The content to render
     * @param array        $styles  The styles to apply
     */
    public function __construct($content, array $styles = [])
    {
        // Create a new Graphite object to allow styling
        $this->graphite = new Graphite;

        // Prepare the content for rendering
        $this->prepareContent($content);

        // Merge the passed styles with the defaults
        $this->styles = array_merge($this->styles, $styles);
    }

    /**
     * Render the box and it's contents.
     *
     * @return string The rendered box
     */
    public function render()
    {
        $content = $this->content;
        $content = $this->addPadding($content);
        $content = $this->addBorder($content);
        $content = $this->addMargin($content);

        return implode("\n", $content);
    }

    /**
     * Render the box when the object is converted to a string.
     *
     * @return string The rendered box
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Set the title of the box.
     *
     * @param string $title The title
     */
    public function setTitle($title = null)
    {
        $this->title = $title;
    }

    /**
     * Prepare the content for rendering.
     *
     * @param string|array $content A string, or an array of strings
     *
     * @return array
     */
    private function prepareContent($content)
    {
        // If a string is passed, separate it by newline characters
        if (is_string($content)) {
            $content = explode("\n", $content);
        }

        // Loop through the lines, and set the box's width to that
        // of the longest line
        $this->width = 0;
        foreach ($content as $line) {
            $len = mb_strlen($this->graphite->strip($line), 'UTF-8');
            if ($len > $this->width) {
                $this->width = $len;
            }
        }

        // Pad each line of the content to the box's width
        foreach ($content as $i => $line) {
            $len         = mb_strlen($this->graphite->strip($line), 'UTF-8');
            $content[$i] = $line.$this->graphite->repeat(' ', ($this->width - $len));
        }

        // Store the array of lines
        return $this->content = $content;
    }

    /**
     * Add padding around the content.
     *
     * @param array $content The content, without padding
     *
     * @return array The content, with padding
     */
    private function addPadding(array $content = [])
    {
        // Create a line of whitespace to create vertical padding
        $pad = $this->graphite->repeat(' ', $this->width);

        // Add the line of whitespace to the content
        // once for each line of padding
        $size = 0;
        while ($size++ < $this->styles['paddingY']) {
            array_unshift($content, $pad);
            array_push($content, $pad);
        }

        // Create a string of whitespace the width of our horizontal padding
        $pad = $this->graphite->repeat(' ', $this->styles['paddingX']);

        // Append and prepend the padding to each line of content
        foreach ($content as $i => $line) {
            $content[$i] = $pad.$line.$pad;
        }

        return $content;
    }

    /**
     * Add a border around the content.
     *
     * @param string $content The content, without border
     *
     * @return array The content, with border
     */
    private function addBorder(array $content = [])
    {
        // Get the full width, including padding
        $width = $this->width + (2 * $this->styles['paddingX']);

        // Get and color the character used for the left and right sides
        $color = $this->graphite->setColor($this->styles['borderColor']);
        $side  = $color->encode($this->styles['borderStyle']['vertical']);

        // Append and prepend the left/right character to each line of content
        foreach ($content as $i => $line) {
            $content[$i] = $side.$line.$side;
        }

        // Create the top border
        $char = $this->styles['borderStyle']['horizontal'];
        if ($this->title !== null) {
            $title = str_pad($this->title, strlen($this->title) + 2, ' ', STR_PAD_BOTH);
            $top   = $title.$this->graphite->repeat($char, ($width - strlen($title)));
        } else {
            $top = $this->graphite->repeat($char, $width);
        }

        $topLeft  = $this->styles['borderStyle']['topLeft'];
        $topRight = $this->styles['borderStyle']['topRight'];
        $top      = $topLeft.$top.$topRight;

        // Color the top border
        $color = $this->graphite->setColor($this->styles['borderColor']);
        $top   = $color->encode($top);

        // Add the top border to the content
        array_unshift($content, $top);

        // Create the bottom border
        $bottom      = $this->graphite->repeat($this->styles['borderStyle']['horizontal'], $width);
        $bottomLeft  = $this->styles['borderStyle']['bottomLeft'];
        $bottomRight = $this->styles['borderStyle']['bottomRight'];
        $bottom      = $bottomLeft.$bottom.$bottomRight;

        // Color the bottom border
        $color  = $this->graphite->setColor($this->styles['borderColor']);
        $bottom = $color->encode($bottom);

        // Add the bottom border to the content
        array_push($content, $bottom);

        return $content;
    }

    /**
     * Add a margin around the content.
     *
     * @param string $content The content, without margin
     *
     * @return array The content, with margin
     */
    private function addMargin(array $content = [])
    {
        // Add a blank line at the top and bottom of the content
        // for each line of padding
        $size = 0;
        while ($size++ < $this->styles['marginY']) {
            array_unshift($content, '');
            array_push($content, '');
        }

        // Add whitespace at either of end of each line
        // to create the horizontal padding
        $pad = $this->graphite->repeat(' ', $this->styles['marginX']);
        foreach ($content as $i => $line) {
            $content[$i] = $pad.$line.$pad;
        }

        return $content;
    }
}
