describe("render user api container", function(){
    let mainPage
    let apiWrapper
    beforeEach( function(client, done){
        mainPage = client
            .url("localhost:3230")
            .page.main()

        client.waitForElementVisible(".download-url-input:not([disabled])", 5000)
            .pause(5000)
            .clearValue(".download-url-input")
            .setValue(".download-url-input", "http://localhost:3230/test-specs/petstore.json")
            .click("button.download-url-button")
            .pause(1000)

        apiWrapper = mainPage.section.apiWrapper

        done()
    })
    afterEach(function (client, done) {
        done()
    })
    it("test rendered user container", function(client){
        apiWrapper.waitForElementVisible("@userAPIWrapper", 5000)
            .expect.element("@userAPIWrapper").