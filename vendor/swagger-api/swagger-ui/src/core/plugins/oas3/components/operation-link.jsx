.model
{
    font-size: 12px;
    font-weight: 300;

    @include text_code();

    .deprecated
    {
        span,
        td
        {
            color: $model-deprecated-font-color !important;
        }

        > td:first-of-type {
            text-decoration: line-through;
        }
    }
    &-toggle
    {
        font-size: 10px;

        position: relative;
        top: 6px;

        display: inline-block;

        margin: auto .3em;

        cursor: pointer;
        transition: transform .15s ease-in;
        transform: rotate(90deg);
        transform-origin: 50% 50%;

        &.collapsed
        {
            transform: rotate(0deg);
        }

        &:after
        {
            display: block;

            width: 20px;
            height: 20px;

            content: '';

            background: url(data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%0A%20%20%20%20%3Cpath%20d%3D%22M10%206L8.59%207.41%2013.17%2012l-4.58%204.59L10%2018l6-6z%22/%3E%0A%3C/svg%3E%0A) center center no-repeat;
            background-size: 100%;
        }
    }

    &-jump-to-path
    {
        position: relative;

        cursor: point