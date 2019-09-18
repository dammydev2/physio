/* eslint-env mocha */
import React from "react"
import expect from "expect"
import { render } from "enzyme"
import { fromJS } from "immutable"
import Info, { InfoUrl } from "components/info"
import { Link } from "components/layout-utils"
import Markdown from "components/providers/markdown"

describe("<Info/> Anchor Target Safety", function(){
	const dummyComponent = () => null
	const components = {
		Markdown,
		InfoUrl,
		Link
	}
	const baseProps = {
		getComponent: c => components[c] || dummyComponent,
		host: "example.test",
		basePath: "/api",
		info: fromJS({
			title: "Hello World"
		})
	}

	it("renders externalDocs links with safe `rel` attributes", function () {
		const props = {
			...baseProps,
			externalDocs: fromJS({
				url: "http://google.com/"
			})
		}
		let wrapper = render(<Info {...props} />)
		const anchor = wrapper.find("a")

		expect(anchor.html()).toEqual("http://google.com/")
		expect(anchor.attr("target")).toEqual("_blank")
		expect(anchor.attr("rel") || "").toInclude("noopener")
		ex