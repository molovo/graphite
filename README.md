# Graphite

A collection of helpers for building pretty command-line tools.

```php
$graphite = new Molovo\Graphite\Graphite;

echo $graphite->green->underline('I believe in unicorns!');
```

## Installation

Install using composer:

```sh
composer require molovo/graphite
```

## Basic Usage

Once instantiated, the Graphite class becomes a re-usable string rendering tool. It comes with a number of methods for styling the foreground color, background color and style of a string using ANSI escape codes.

```php
echo $graphite->red('A string');
// "\033[0;31mA string\033[0;m"
```

When accessed as a property, or as a method without arguments, these methods allow chaining of styles. When a string is passed as the first argument, it is returned with all the chained styles applied.

```php
echo $graphite->red->yellowbg()->underline('A string');
// "\033[4;31;43mA string\033[0;m"
```

Global indentation can be set with the `setGlobalIndentation()` method. Once defined, when the `render()` method is invoked, the string passed to it is output with the defined indentation prepended to it, and with a newline appended.

```php
$graphite->setGlobalIndentation(2);
echo $graphite->yellow->render('A string');
// "  \033[0;33mA string\033[0;m\n"
```

#### Available styling methods

* `black`
* `blackbg`
* `red`
* `redbg`
* `green`
* `greenbg`
* `yellow`
* `yellowbg`
* `blue`
* `bluebg`
* `magenta`
* `magentabg`
* `cyan`
* `cyanbg`
* `white`
* `whitebg`
* `gray`
* `graybg`
* `bold`
* `italic`
* `underline`
* `inverse`
* `strikethrough`

## Additional Methods

As well as the styling methods above, the following helper methods are available.

#### `strip(string $str)`

The `strip()` method strips all ANSI escape codes from the passed string and returns it.

```php
$str = "\033[0;31mA string with ANSI escaping\033[0;m"
echo $graphite->strip($str);
// "A string with ANSI escaping"
```

#### `repeat(string $character, int $length)`

The `repeat()` method returns a string consisting of the defined `$character`, repeated `$length` times.

```php
echo $graphite->repeat('+', 5);
// "+++++"
```

## Boxes

The `Box` class adds a border around a string (or an array of strings, where each item is a line).

```php
$box = new Molovo\Graphite\Box('Rainbows!');
echo $box;
// ┌─────────┐
// │Rainbows!│
// └─────────┘
```

Boxes can be styled in a number of ways, by passing an array of styles as a second parameter.

```php
$box = new Box('Unicorns!', [
  'borderColor' => Graphite::WHITE, // The color of the border (and title)
  'borderStyle' => Box::SINGLE,    // The border style
  'marginX'     => 0,               // The number of characters to add to left and right
  'marginY'     => 0,               // The number of lines to add above and below
  'paddingX'    => 0,               // The number of characters to add as left and right padding
  'paddingY'    => 0,               The number of lines to add as top and bottom padding
]);
```

The following styles are available:

```
Box::SINGLE
┌──────────┐
│The string│
└──────────┘

Box::DOUBLE
╔══════════╗
║The string║
╚══════════╝

Box::ROUNDED
╭──────────╮
│The string│
╰──────────╯

Box::SINGLE_DOUBLE
╓──────────╖
║The string║
╙──────────╜

Box::DOUBLE_SINGLE
╒══════════╕
│The string│
╘══════════╛

Box::CLASSIC
+----------+
|The string|
+----------+
```

You can also use the `Box::NO_BORDER` to add margin and padding to a string without a border.

```php
$box = new Box("Unicorns and\nrainbows", [
  'borderStyle' => Box::NO_BORDER,
  'paddingX' => 2,
  'paddingY' => 1
]);
echo $box;
//
//   Unicorns and
//   rainbows
//
```

You can also add titles to boxes. These are rendered within the border, in the same color.

```php
$box = new Box('The string', ['paddingX' => 1]);
$box->setTitle('My Box');
echo $box;
// ┌ My Box ────┐
// │ The string │
// └────────────┘
```

## Tables

```php
$data = [
    ['First', 'Second', 'Third'],
    [1, 2, 3],
    [11, 22, 33],
];

$table = new Table($data, [
    'cellPadding'     => 1,
    'columnSeparator' => '│',
    'headerColor'     => Graphite::WHITE,
    'headerSeparator' => '═',
    'marginX'         => 0,
    'marginY'         => 0,
    'separatorColor'  => Graphite::WHITE,
]);
echo $table;

// First │ Second │ Third
// ══════════════════════════
// 1     │ 2      │ 3
// 11    │ 22     │ 33
