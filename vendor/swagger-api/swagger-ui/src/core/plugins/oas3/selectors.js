(max-width: 768px) {
          font-size: 12px;
        }


        display: flex;
        align-items: center;

        word-break: break-word;

        padding: 0 10px;

        @include text_code();

    }

    .opblock-summary-path__deprecated
    {
        text-decoration: line-through;
    }

    .opblock-summary-operation-id
    {
        font-size: 14px;
    }

    .opblock-summary-description
    {
        font-size: 13px;

        flex: 1 1 auto;

        word-break: break-word;

        @include text_body();
    }

    .opblock-summary
    {
        display: flex;
        align-items: center;

        padding: 5px;

        cursor: pointer;

        .view-line-link
        {
            position: relative;
            top: 2px;

            width: 0;
            margin: 0;

            cursor: pointer;
            transition: all .5s;
        }

        &:hover
        {
            .view-line-link
            {
                width: 18px;
                margin: 0 5px;
            }
        }
    }



    &.opblock-post
    {
        @include method($_color-post);
    }

    &.opblock-put
    {
        @include method($_color-put);
    }

    &.opblock-delete
    {
        @include method($_color-delete);
    }

    &.opblock-get
    {
        @include method($_color-get);
    }

    &.opblock-patch
    {
        @include method($_color-patch);
    }

    &.opblock-head
    {
        @include method($_color-head);
    }

    &.opblock-options
    {
        @include method($_color-options);
    }

    &.opblock-deprecated
    {
        opacity: .6;

        @include method($_color-disabled);
    }

    .opblock-schemes
    {
        padding: 8px 20px;

        .schemes-title
        {
            padding: 0 10px 0 0;
        }
    }
}

.filter
{
    .operation-filter-input
    {
        width: 100%;
        margin: 20px 0;
        padding: 10px 10px;

        border: 2px solid $operational-filter-input-border-color;
    }
}


.tab
{
    display: flex;

    margin: 20px 0 10px 0;
    padding: 0;

    list-style: none;

    li
    {
        font-size: 12px;

        min-width: 60px;
        padding: 0;

        cursor: pointer;

        @include text_headline();

        &:first-of-type
        {
            position: relative;

            padding-left: 0;
            padding-right: 12px;

            &:after
            {
                position: absolute;
                top: 0;
                right: 6px;

                width: 1px;
                height: 100%;

                content: '';

                background: rgba($tab-list-item-first-background-color,.2);
            }
        }

        &.active
        {
            font-weight: bold;
        }
    }
}

.opblock-description-wrapper,
.opblock-external-docs-wrapper,
.opblock-title_normal
{
    font-size: 12px;

    margin: 0 0 5px 0;
    padding: 15px 20px;

    @include text_body();

    h4
    {
        font-size: 12px;

        margin: 0 0 5px 0;

        @include text_body();
    }

    p
    {
        font-size: 14px;

        margin: 0;

        @include