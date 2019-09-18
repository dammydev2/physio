import expect from "expect"
import Im from "immutable"
import curl from "core/curlify"
import win from "core/window"

describe("curlify", function() {

    it("prints a curl statement with custom content-type", function() {
        var req = {
            url: "http://example.com",
            method: "POST",
            body: {
                id: 0,
                name: "doggie",
                status: "available"
            },
            headers: {
                Accept: "application/json",
                "content-type": "application/json"
            }
        }

        let curlified = curl(Im.fromJS(req))

        expect(curlified).toEqual("curl -X POST \"http://example.com\" -H  \"Accept: application/json\" -H  \"content-type: application/json\" -d {\"id\":0,\"name\":\"doggie\",\"status\":\"available\"}")
    })

    it("does not change the case of header in curl", function() {
        var req = {
            url: "http://example.com",
            method: "POST",
            headers: {
                "conTenT Type": "application/Moar"
            }
        }

        let curlified = curl(Im.fromJS(req))

        expect(curlified).toEqual("curl -X POST \"http://example.com\" -H  \"conTenT Type: application/Moar\"")
    })

    it("prints a curl statement with an array of query params", function() {
        var req = {
            url: "http://swaggerhub.com/v1/one?name=john|smith",
            method: "GET"
        }

        let curlified = curl(Im.fromJS(req))

        expect(curlified).toEqual("curl -X GET \"http://swaggerhub.com/v1/one?name=john|smith\"")
    })

    it("prints a curl statement with an array of query params and auth", function() {
        var req = {
            url: "http://swaggerhub.com/v1/one?name=john|smith",
            method: "GET",
            headers: {
                authorization: "Basic Zm9vOmJhcg=="
            }
        }

        let curlified = curl(Im.fromJS(req))

        expect(curlified).toEqual("curl -X GET \"http://swaggerhub.com/v1/one?name=john|smith\" -H  \"authorization: Basic Zm9vOmJhcg==\"")
    })

    it("prints a curl statement with html", function() {
        var req = {
            url: "http://swaggerhub.com/v1/one?name=john|smith",
            method: "GET",
            headers: {
                accept: "application/json"
            },
            body: {
                description: "<b>Test</b>"
            }
        }

        let curlified = curl(Im.fromJS(req))

        expect(curlified).toEqual("curl -X GET \"http://swaggerhub.com/v1/one?name=john|smith\" -H  \"accept: application/json\" -d {\"description\":\"<b>Test</b>\"}")
    })

    it("handles post body with html", function() {
        var req = {
            url: "http://swaggerhub.com/v1/one?name=john|smith",
            method: "POST",
            headers: {
                accept: "application/json"
            },
            body: {
                description: "<b>Test</b>"
            }
        }

        let curlified = curl(Im.fromJS(req))

        expect(curlified).toEqual("curl -X POST \"http://swaggerhub.com/v1/one?name=john|smith\" -H  \"accept: application/json\" -d {\"description\":\"<b>Test</b>\"}")
    })

    it("handles post body with special chars", function() {
        var req = {
            url: "http://swaggerhub.com/v1/one?name=john|smith",
            method: "POST",
            body: {
                description: "@prefix nif:<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/nif-core#> .\n" +
                "@prefix itsrdf: <http://www.w3.org/2005/11/its/rdf#> ."
            }
        }

        let curlifie