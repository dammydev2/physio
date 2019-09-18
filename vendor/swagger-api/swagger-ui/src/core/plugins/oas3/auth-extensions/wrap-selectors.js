size: 11px;

        @include text_code($response-col-links-font-color);
    }
}

.response-col_description__inner
{
    div.markdown, div.renderedMarkdown
    {
        font-size: 12px;
        font-style: italic;

        display: block;

        margin: 0;
        padding: 10px;

        border-radius: 4px;
        background: $response-col-description-inner-markdown-background-color;

        @include text_code($response-col-description-inner-markdown-font-color);

        p
        {
            margin: 0;
            @include text_code($response-col-description-inner-markdown-font-color);
        }

        a
        {
            @include text_code($response-col-description-inner-markdown-link-font-color);
            text-decoration: underline;
            &:hover {
                color: $response-col-description-inner-markdown-link-font-color-hover;
            }
        }

        th
        {
            @include text_code($response-col-description-inner-markdown-font-color);
            border-bottom: 1px solid $response-col-description-inner-markdown-font-color;
        }
    }
}

.opblock-body
{
  .opblock-loading-animation
  {
    display: block;
    margin: 3em;
    margin-left: auto;
    margin-right: auto;
  }
}

.opblock-body pre
{
    font-size: 12px;

    margin: 0;
    padding: 10px;

    white-space: pre-wrap;
    word-wrap: break-word;
    word-break: break-all;
    word-break: break-word;
    hyphens: auto;

    border-radius: 4px;
    background: $opblock-body-background-color;

    overflow-wrap: break-word;
    @include text_code($opblock-body-font-color);
    span
    {
        color: $opblock-body-font-color !important;
    }

    .headerline
    {
        display: block;
    }
}

.highlight-code {
  position: relative;

  > .microlight {
    overflow-y: auto;
    max-hei