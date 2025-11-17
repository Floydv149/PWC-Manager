//Load general
import { generateCartContentHTML } from "./scripts/cartContentRenderer.js";

const app = document.getElementById("app");

async function loadPage(pageName) {
	try {
		// Fetch the HTML file
		const response = await fetch(`pages/${pageName}.html`);
		if (!response.ok) throw new Error("Page not found");

		const html = await response.text();
		app.innerHTML = html;
		addButtonRedirects();
	} catch (err) {
		app.innerHTML = `<p>Error loading page: ${err.message}</p>`;
	}
}

function addButtonRedirects() {
	const buttons = document.querySelectorAll("button[data-page]");
	buttons.forEach((button) => {
		button.addEventListener("click", () => {
			const pageName = button.getAttribute("data-page");
			loadPage(pageName);
		});
	});
}

loadPage("home");
