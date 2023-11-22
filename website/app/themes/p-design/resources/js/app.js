window.$ = window.jQuery = require("jquery")

window.Cookies = require("./lib/cookies")
window.$script = require("scriptjs")

$(function () {
	require("./lib/svg")
	require("./lib/slick")

	require("./modules/header")
	require("./modules/slider")
	require("./modules/animations")
	require("./modules/tabs")
	require("./modules/popup")
	require("./modules/realisations")
	require("./modules/auth")
	require("./modules/history")
	require("./modules/rgpd")
	require("./modules/gmap")
})
