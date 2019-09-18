select
{
    font-size: 14px;
    font-weight: bold;

    padding: 5px 40px 5px 10px;

    border: 2px solid $form-select-border-color;
    border-radius: 4px;
    background: $form-select-background-color url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMCAyMCI+ICAgIDxwYXRoIGQ9Ik0xMy40MTggNy44NTljLjI3MS0uMjY4LjcwOS0uMjY4Ljk3OCAwIC4yNy4yNjguMjcyLjcwMSAwIC45NjlsLTMuOTA4IDMuODNjLS4yNy4yNjgtLjcwNy4yNjgtLjk3OSAwbC0zLjkwOC0zLjgzYy0uMjctLjI2Ny0uMjctLjcwMSAwLS45NjkuMjcxLS4yNjguNzA5LS4yNjguOTc4IDBMMTAgMTFsMy40MTgtMy4xNDF6Ii8+PC9zdmc+) right 10px center no-repeat;
    background-size: 20px;
    box-shadow: 0 1px 2px 0 rgba($form-select-box-shadow-color, .25);

    @include text_headline();
    appearance: none;

    &[multiple]
    {
        margin: 5px 0;
        padding: 5px;

        background: $form-select-background-color;
    }

    &.invalid {
        @include invalidFormElement();
    }
}

.o