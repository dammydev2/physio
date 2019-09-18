.info
{
    margin: 50px 0;

    hgroup.main
    {
        margin: 0 0 20px 0;
        a
        {
            font-size: 12px;
        }
    }
    pre 
    {
        font-size: 14px;
    }
    p, li, table
    {
        font-size: 14px;

        @include text_body();
    }

    h1, h2, h3, h4, h5
    {
        @include text_body();
    }

    a
    {
        font-size: 14px;

        transition: all .4s;

        @include text_body($info-link-font-color);

        &:hover
        {
            color: darken($info-link-font-color-hover, 15%);
        }
    }
    > div
    {
        margin: 0 0 5px 0;
    }

    .base-url
    {
        font-size: 12px;
        font-weight: 300 !important;

        margin: 0;

        @include text_code();
    }

    .title
    {
        font-size: 36px;

        margin: 0;

        @include text_body();

        small
        {
  