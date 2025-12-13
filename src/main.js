//Load general
import { generateCartContentHTML } from "./scripts/cartContentRenderer.js";
import {
	openModal,
	openYesNoModal,
	openAlertModal,
	closeLastModal,
} from "./scripts/modalRenderer.js";
import { fullScreen, closeFullScreen } from "./scripts/fullScreen.js";

window.generateCartContentHTML = generateCartContentHTML;
window.openModal = openModal;
window.openAlertModal = openAlertModal;
window.openYesNoModal = openYesNoModal;
window.closeLastModal = closeLastModal;
window.fullScreen = fullScreen;
window.closeFullScreen = closeFullScreen;
window.loadPage = loadPage;
window.navigateBack = navigateBack;

const app = document.getElementById("app");
window.defaultData = {
	cart: {
		number: 0,
		name: "",
		locationID: 0,
		responsible: "",
		status: 0,
		lastCleaned: "0000-00-00",
		supplies: {},
		type: 0,
		designID: 0,
		comments: "",
	},
	location: {
		name: "",
		address: "",
	},
};
window.data = {
	carts: {
		0: {
			number: 1,
			name: "Cart 1",
			locationID: 0,
			responsible: "John Doe",
			status: 0,
			lastCleaned: "0000-00-00",
			supplies: {
				1: 2,
			},
			type: 0,
			designID: 0,
			comments: "Dit is een test-opmerking.",
		},
		1: {
			number: 2,
			name: "Cart 2",
			locationID: 0,
			responsible: "Jane Smith",
			status: 0,
			lastCleaned: "0000-00-00",
			supplies: {
				1: 4,
			},
			type: 0,
			designID: 0,
			comments: "",
		},
	},
	designs: {
		0: {
			name: "November 2025",
			creationDate: "0000-00-00",
			coverID: 0,
			publications: [
				[0, 0],
				[1, 1],
				[2, 2],
			],
		},
	},
	locations: {
		0: {
			name: "Station Hemiksem",
			address: "Europalaan 27, 2620 Hemiksem",
		},
		1: {
			name: "Tuba Aartselaar",
			address: "Koninkrijkszaal",
		},
	},
	catalog: {
		0: {
			title: "Alles wordt duurder wat nu?",
			imageURL:
				"https://cms-imgp.jw-cdn.org/img/p/g/202511/O/pt/g_O_202511_lg.jpg",
		},
		1: {
			title: "Waar is het respect gebleven?",
			imageURL:
				"https://cms-imgp.jw-cdn.org/img/p/g/202411/O/pt/g_O_202411_lg.jpg",
		},
		2: {
			title: "Is er hoop voor onze planeet?",
			imageURL:
				"https://cms-imgp.jw-cdn.org/img/p/g/202311/O/pt/g_O_202311_lg.jpg",
		},
	},
	covers: {
		0: {
			name: "JW.ORG",
			fileName: "JWORG.png",
		},
	},
};

window.saveData = saveData;

window.session = {
	currentModalAnswer: -1,
	currentCartIndex: 1,
	newCart: false,
	isRealisticView: false,
	currentPage: "",
	pageHistory: [],
};

async function loadPage(pageName, saveHistory = true) {
	try {
		setTimeout(async () => {
			app.style.opacity = 0;
			// Fetch the HTML file
			const response = await fetch(`pages/${pageName}.html`);
			if (!response.ok) throw new Error("Page not found");

			const html = await response.text();

			if (saveHistory) {
				session.pageHistory.push(session.currentPage);
				session.currentPage = pageName;
			}
			loadPageContent(html, pageName);
		}, 0);
	} catch (err) {
		// app.innerHTML = `<p>Error loading page: ${err.message}</p>`;
		alert("Something went wrong with this button.");
	}
}

function loadPageContent(html, pageName) {
	setTimeout(() => {
		app.innerHTML = html;

		removeOldScripts();

		const scripts = app.querySelectorAll("script");
		scripts.forEach((oldScript) => {
			const newScript = document.createElement("script");

			if (oldScript.src) {
				newScript.src = oldScript.src;
			} else {
				newScript.textContent = oldScript.textContent;
			}

			newScript.setAttribute("page-script", pageName);

			document.body.appendChild(newScript);
			oldScript.remove();
		});

		addButtonRedirects();
		window.scrollTo({
			top: 0,
			behavior: "instant",
		});

		setTimeout(() => {
			app.style.opacity = 1;
		}, 0);
	}, 150);
}

function navigateBack() {
	if (
		session.pageHistory.length > 0 &&
		session.pageHistory[session.pageHistory.length - 1] != ""
	) {
		loadPage(session.pageHistory.pop(), false);
	} else {
		loadPage("home", false);
	}
}

function removeOldScripts() {
	document.querySelectorAll("script[page-script]").forEach((s) => s.remove());
}

function addButtonRedirects() {
	const buttons = document.querySelectorAll("*[data-page]");
	buttons.forEach((button) => {
		button.addEventListener("click", () => {
			const pageName = button.getAttribute("data-page");
			loadPage(pageName);
		});
	});
}

function loadData() {
	if (localStorage.getItem("data")) {
		data = JSON.parse(localStorage.getItem("data"));
	} else {
		saveData();
	}
}

function saveData() {
	localStorage.setItem("data", JSON.stringify(data));
	console.log("Data saved.");
}

loadData();
session.currentCartIndex = 1;
loadPage("cartDetails");
