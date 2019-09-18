// - - - - - - - - - - - - - - - - - - -
// - - _mixins.scss module
// styles for the _mixins.scss module
@function calculateRem($size)
{
    $remSize: $size / 16px;
    @return $remSize * 1rem;
}

@mixin font-size($size)
{
    font-size: $size;
    font-size: calculateRem($size);
}

%clearfix
{
    *zoom: 1;
    &:before,
    &:after
    {
        display: table;

        content: ' ';
    }
    &:after
    {
        clear: both;
    }
}

@mixin size($width, $height: $width)
{
    width: $width;
    height: $height;
}

$ease: (
  in-quad:      cubic-bezier(.550,  .085, .680, .530),
  in-cubic:     cubic-bezier(.550,  .055, .675, .190),
  in-quart:     cubic-bezier(.895,  .030, .685, .220),
  in-quint:     cubic-bezier(.755,  .050, .855, .060),
  in-sine:      cubic-bezier(.470,  .000, .745, .715),
  in-expo:      cubic-bezier(.950,  .050, .795, .035),
  in-circ:      cubic-bezier(.600,  .040, .980, .335),
  in-back:      cubic-bezier(.600, -.280, .735, .045),
  out-quad:     cubic-bezier(.250,  .460, .450, .940),
  out-cubic:    cubic-bezier(.215,  .610, .355, 1.000),
  out-quart:    cubic-bezier(.165,  .840, .440, 1.000),
  out-quint:    cubic-bezier(.230,  1.000, .320, 1.000),
  out-sine:     cubic-bezier(.390,  .575, .565, 1.000),
  out-expo:     cubic-bezier(.190,  1.000, .220, 1.000),
  out-circ:     cubic-bezier(.075,  .820, .165, 1.000),
  out-back:     cubic-bezier(.175,  .885, .320, 1.275),
  in-out-quad:  cubic-bezier(.455,  .030, .515, .955),
  in-out-cubic: cubic-bezier(.645,  .045, 