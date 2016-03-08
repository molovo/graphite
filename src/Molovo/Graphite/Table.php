<?php

namespace Molovo\Graphite;

class Table
{
    /**
     * The default table styles.
     *
     * @var array
     */
    private $styles = [
        'cellPadding'     => 1,
        'columnSeparator' => '│',
        'headerColor'     => Graphite::WHITE,
        'headerSeparator' => '═',
        'marginX'         => 0,
        'marginY'         => 0,
        'separatorColor'  => Graphite::WHITE,
    ];

    /**
     * The data to include in the table.
     *
     * @var array
     */
    private $data = [];

    /**
     * Create the table object.
     *
     * @param array $data   The data to render
     * @param array $styles The styles to apply
     */
    public function __construct(array $data, array $styles = [])
    {
        $this->graphite = new Graphite;
        $this->styles   = array_merge($this->styles, $styles);
        $this->prepareData($data);
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
     * Prepare data for rendering as a table.
     *
     * @param array $data The data to prepare
     *
     * @return array The data, with columns padded
     */
    private function prepareData(array $data)
    {
        // Create an array to store column widths in
        $colWidths = [];

        // Loop through the data once to find the maximum
        // width for each column
        foreach ($data as $row) {
            foreach ($row as $column => $value) {
                $len = strlen($this->graphite->strip($value));
                if (!isset($colWidths[$column])) {
                    $colWidths[$column] = $len;
                    continue;
                }

                if ($colWidths[$column] < $len) {
                    $colWidths[$column] = $len;
                }
            }
        }

        // Loop through the data again, and pad each value to
        // the column width
        foreach ($data as $i => $row) {
            foreach ($row as $column => $value) {
                $len               = $colWidths[$column];
                $data[$i][$column] = str_pad((string) $value, $len, ' ');
            }
        }

        return $this->data = $data;
    }

    public function render()
    {
        $data = $this->data;

        // Separate the header from the data, and colorise it
        $header = array_shift($data);
        foreach ($header as $i => $value) {
            $color      = $this->graphite->setColor($this->styles['headerColor']);
            $header[$i] = $color->encode($value);
        }

        // Create the column separator
        $color     = $this->graphite->setColor($this->styles['separatorColor']);
        $char      = $color->encode($this->styles['columnSeparator']);
        $pad       = $this->graphite->repeat(' ', $this->styles['cellPadding']);
        $separator = $pad.$char.$pad;

        // Start our output with the header row
        $header = implode($separator, $header);
        $output = [$header];

        // Create the header separator and add it to the output
        if ($this->styles['headerSeparator']) {
            $char         = $this->styles['headerSeparator'];
            $len          = mb_strlen($this->graphite->strip($header), 'UTF-8');
            $color        = $this->graphite->setColor($this->styles['separatorColor']);
            $rowSeparator = $this->graphite->repeat($char, $len);
            $rowSeparator = $color->encode($rowSeparator);
            $output[]     = $rowSeparator;
        }

        // Implode and add each row of data
        foreach ($data as $row) {
            $output[] = implode($separator, $row);
        }

        if (($marginX = $this->styles['marginX']) > 0) {
            foreach ($output as $row) {
                $row = $this->graphite->repeat(' ', $marginX);
            }
        }

        if (($marginY = $this->styles['marginY']) > 0) {
            $i = 0;
            while ($i++ < $marginY) {
                array_unshift($output, '');
                array_push($output, '');
            }
        }

        return implode("\n", $output);
    }
}
