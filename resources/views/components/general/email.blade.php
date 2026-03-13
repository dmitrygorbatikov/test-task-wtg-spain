@props(['email', 'color' => '#1c3675'])
<a
    href="mailto:{{ $email }}"
    style="
    color: {{ $color }};
    font-weight: 700;
    text-decoration: none;
  "
>{{ $email }}</a>
