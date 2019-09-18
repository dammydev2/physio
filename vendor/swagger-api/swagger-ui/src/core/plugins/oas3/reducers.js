.wrapper
{
    width: 100%;
    max-width: 1460px;
    margin: 0 auto;
    padding: 0 20px;
    box-sizing: border-box;
}

.opblock-tag-section
{
    display: flex;
    flex-direction: column;
}

.opblock-tag
{
    display: flex;
    align-items: center;

    padding: 10px 20px 10px 10px;

    cursor: pointer;
    transition: all .2s;

    border-bottom: 1px solid rgba($opblock-tag-border-bottom-color, .3);

    &:hover
    {
        background: rgba($opblock-tag-background-color-hover,.02);
    }
}

@mixin method($color)
{
    border-color: $color;
    background: rgba($color, .1);

    .opblock-summary-method
    {
        background: $color;
    }

    .opblock-summary
    {
        border-color: $color;
    }

    .tab-header .tab-item.active h4 span:after
    {
        background: $color;
    }
}




.opblock-tag
{
    font-size: 24px;

    margin: 0 0 5px 0;

    @include text_headline();

    &.no-desc
    {
        span
        {
            flex: 1;
        }
    }

    svg
    {
        transition: all .4s;
    }

    small
    {
        font-size: 14px;
        font-weight: normal;

        flex: 1;

        padding: 0 10px;

        @include text_body();
    }
}

.parameter__type
{
    font-size: 12px;

    padding: 5px 0;

   