pen try-it-out
        .click("#operations-default-get_regularParams")
        .waitForElementVisible("button.btn.try-out__btn", 5000)
        .click("button.btn.try-out__btn")
        .pause(200)

      client // set parameter, to ensure an initial value is set
        .click(`${inputSelector} .json-schema-form-item-add`)
        .setValue(`${inputSelector} input`, "asdf")
        .click("button.btn.execute.opblock-control__btn")
        .pause(200)

      client // remove initial value, execute again
        .click(`${inputSelector} .json-schema-form-item-remove`)
        .pause(200)
        .click("button.btn.execute.opblock-control__btn")
        .expect.element("textarea.curl").text
        .to.contain(`GET "htt