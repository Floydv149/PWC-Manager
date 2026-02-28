//Load general
import { generateCartContentHTML } from "./scripts/cartContentRenderer.js";
import {
	openModal,
	openYesNoModal,
	openAlertModal,
	closeLastModal,
} from "./scripts/modalRenderer.js";
import {
	fullScreen,
	closeFullScreen,
	isFullScreenOpen,
} from "./scripts/fullScreen.js";
import { getAvailableIndex } from "./scripts/findAvailableIndex.js";

//Capacitor plugin settings
document.addEventListener("DOMContentLoaded", () => {
	//In native app?
	if (window.Capacitor) {
		const { App } = Capacitor.Plugins;

		//Assign app navigation to back button
		App.addListener("backButton", () => {
			navigateBack();
		});
	} else {
		window.history.pushState(
			{ page: 1 },
			"Web Back Handler",
			window.location.href,
		);

		window.addEventListener("popstate", () => {
			navigateBack();

			setTimeout(() => {
				window.history.pushState(
					{ page: 1 },
					"Web Back Handler",
					window.location.href,
				);
			}, 0);
		});

		document.getElementById("webBackButton").style.display = "block";
	}
});

window.generateCartContentHTML = generateCartContentHTML;
window.openModal = openModal;
window.openAlertModal = openAlertModal;
window.openYesNoModal = openYesNoModal;
window.closeLastModal = closeLastModal;
window.fullScreen = fullScreen;
window.closeFullScreen = closeFullScreen;
window.isFullScreenOpen = isFullScreenOpen;
window.loadPage = loadPage;
window.reloadPage = reloadPage;
window.navigateBack = navigateBack;
window.getAvailableIndex = getAvailableIndex;

const app = document.getElementById("app");
window.defaultData = {
	cart: {
		number: 0,
		name: "",
		locationID: "",
		responsible: "",
		statusID: 0,
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
	statusses: {
		0: {
			name: "Ready to use",
			image: "checkBox",
		},
		1: {
			name: "Needs maintenance",
			image: "alert",
		},
		2: {
			name: "Under maintenance",
			image: "clean",
		},
		3: {
			name: "Unavailable",
			image: "close",
		},
	},
	design: {
		name: "Untitled",
		creationDate: "0000-00-00",
		coverID: 0,
		publications: [
			[0, 0],
			[0, 0],
			[0, 0],
		],
	},
	publication: {
		title: "",
		imageURL: "/assets/icons/unknownPublication.png",
		categoryID: 0,
		date: "0000-00-00",
	},
};

window.data = {
	carts: {
		0: {
			number: 1,
			name: "Example cart",
			locationID: 0,
			responsible: "John Doe",
			statusID: 0,
			lastCleaned: "0000-00-00",
			supplies: {},
			type: 0,
			designID: 0,
			comments: "",
		},
	},
	designs: {
		0: {
			name: "Example design",
			creationDate: "0000-00-00",
			coverID: 0,
			publications: [
				[0, 0],
				[0, 0],
				[0, 0],
			],
		},
	},
	locations: {
		0: {
			name: "Kingdom Hall",
			address: "2995 Housels Run Rd Milton PA 17847-9016",
		},
	},
	library: {
		X: {
			title: "Other languages",
			imageURL: "/assets/icons/unknownLanguage.png",
			categoryID: 0,
			date: "0000-00-00",
		},
		0: {
			title: "Unknown",
			imageURL: "/assets/icons/unknownPublication.png",
			categoryID: 0,
			date: "0000-00-00",
		},
	},
	covers: {},
};

window.exampleData = {
	carts: {
		0: {
			number: 1,
			name: "Cart 1",
			locationID: 0,
			responsible: "John Doe",
			statusID: 0,
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
			statusID: 0,
			lastCleaned: "0000-00-00",
			supplies: {
				1: 4,
				0: 3,
			},
			type: 0,
			designID: 1,
			comments: "",
		},
	},
	designs: {
		0: {
			name: "November 2025",
			creationDate: "0000-00-00",
			coverID: 0,
			publications: [
				[1, 1],
				[2, 2],
				[3, 3],
			],
		},
		1: {
			name: "December 2025",
			creationDate: "0000-00-00",
			coverID: 1,
			publications: [
				[2, 2],
				[1, 1],
				[3, 3],
			],
		},
	},
	locations: {
		0: {
			name: "Station",
			address: "Station",
		},
	},
	library: {
		X: {
			title: "Other languages",
			imageURL: "/assets/icons/unknownLanguage.png",
			categoryID: 0,
			date: "0000-00-00",
		},
		0: {
			title: "Unknown",
			imageURL: "/assets/icons/unknownPublication.png",
			categoryID: 0,
			date: "0000-00-00",
		},
		1: {
			title: "Coping with rising prices",
			imageURL:
				"https://cms-imgp.jw-cdn.org/img/p/g/202511/E/pt/g_E_202511_lg.jpg",
			categoryID: 1,
			date: "0000-00-00",
		},
		2: {
			title: "What has happened to respect?",
			imageURL:
				"https://cms-imgp.jw-cdn.org/img/p/g/202411/E/pt/g_E_202411_lg.jpg",
			categoryID: 1,
			date: "0000-00-00",
		},
		3: {
			title: "Can our planet survive?",
			imageURL:
				"https://cms-imgp.jw-cdn.org/img/p/g/202311/E/pt/g_E_202311_lg.jpg",
			categoryID: 1,
			date: "0000-00-00",
		},
	},
	covers: {},
};

window.globals = {
	categories: {
		0: {
			name: "Other",
			jworgCodes: [""],
		},
		1: {
			name: "Awake!",
			jworgCodes: ["g"],
		},
		2: {
			name: "Watchtower",
			jworgCodes: ["wp"],
		},
		3: {
			name: "Tracts & Invitations",
			jworgCodes: ["t", "inv"],
		},
		4: {
			name: "Brochures",
			jworgCodes: ["lffi", "rj", "ypq", "lf", "lc", "ll", "hf", "fg", "ld"],
		},
		5: {
			name: "Books",
			jworgCodes: ["lff", "yp"],
		},
	},
	covers: {
		0: {
			name: "JW.ORG",
			fileName: "JWORG.png",
			categoryID: 0,
		},
		1: {
			name: "A better world is near",
			fileName: "aBetterWorldIsNear.png",
			categoryID: 2,
		},
		2: {
			name: "An end to war - how?",
			fileName: "anEndToWarHow.png",
			categoryID: 2,
		},
		3: {
			name: "Everyone is welcome",
			fileName: "everyoneIsWelcome.png",
			categoryID: 3,
		},
		4: {
			name: "Find relief from stress",
			fileName: "findReliefFromStress.png",
			categoryID: 1,
		},
		5: {
			name: "Free bible course",
			fileName: "freeBibleCourse.png",
			categoryID: 5,
		},
		6: {
			name: "Free online bible course",
			fileName: "freeOnlineBibleCourse.png",
			categoryID: 5,
		},
		7: {
			name: "How did life originate?",
			fileName: "howDidLifeOriginate.png",
			categoryID: 4,
		},
		8: {
			name: "Mental health",
			fileName: "mentalHealth.png",
			categoryID: 2,
		},
		9: {
			name: "Questions about suffering answered",
			fileName: "questionsAboutSufferingAnswered.png",
			categoryID: 1,
		},
		10: {
			name: "What has happened to respect?",
			fileName: "whatHasHappenedToRespect.png",
			categoryID: 1,
		},
		11: {
			name: "Does God care about you?",
			fileName: "doesGodCareAboutYou.png",
			categoryID: 2,
		},
		12: {
			name: "How to achieve real success?",
			fileName: "howToAchieveRealSucces.png",
			categoryID: 0,
		},
		13: {
			name: "No more sadness",
			fileName: "noMoreSadness.png",
			categoryID: 0,
		},
		14: {
			name: "Coping with rising prices",
			fileName: "copingWithRisingPrices.png",
			categoryID: 1,
		},
		15: {
			name: "A world in turmoil - How you can cope",
			fileName: "worldInTurmoil.png",
			categoryID: 1,
		},
		16: {
			name: "Peace - When?",
			fileName: "peaceWhen.png",
			categoryID: 0,
		},
		17: {
			name: "Right and wrong",
			fileName: "rightAndWrong.png",
			categoryID: 2,
		},
		18: {
			name: "The search for truth",
			fileName: "theSearchForTruth.png",
			categoryID: 2,
		},
		19: {
			name: "What is Gods Kingdom?",
			fileName: "whatIsGodsKingdom.png",
			categoryID: 2,
		},
		20: {
			name: "Why pray?",
			fileName: "whyPray.png",
			categoryID: 2,
		},
		21: {
			name: "Breaking the cycle of hatred",
			fileName: "breakingTheCycleOfHatred.png",
			categoryID: 2,
		},
		22: {
			name: "Can our planet survive?",
			fileName: "canOurPlanetSurvive.png",
			categoryID: 1,
		},
		23: {
			name: "Get to know the Bible",
			fileName: "getToKnowTheBible.png",
			categoryID: 0,
		},
		24: {
			name: "Help for those who grieve",
			fileName: "helpForThoseWhoGrieve.png",
			categoryID: 0,
		},
		25: {
			name: "Should you believe in a creator?",
			fileName: "shouldYouBelieveInACreator.png",
			categoryID: 1,
		},
		26: {
			name: "Who is God?",
			fileName: "whoIsGod.png",
			categoryID: 2,
		},
		27: {
			name: "Will we ever feel safe and secure?",
			fileName: "willWeEverFeelSafeAndSecure.png",
			categoryID: 1,
		},
	},
};

window.saveData = saveData;

window.session = {
	currentModalAnswer: -1,
	currentCartIndex: 0,
	currentDesignIndex: 0,
	currentPublicationIndex: 0,
	currentCoverIndex: 0,
	newCart: false,
	newPublication: false,
	isRealisticView: false,
	currentPage: "home",
	pageHistory: [],
	selectedElement: null,
	navigateBackCancelFunction: null,
};

async function loadPage(
	pageName,
	saveHistory = true,
	keepScrollPosition = false,
) {
	session.navigateBackCancelFunction = null;

	if (saveHistory) {
		try {
			const isCapacitorActive = typeof window.Capacitor !== "undefined";

			// Try fetching filesystem plugin
			const Haptics =
				isCapacitorActive && window.Capacitor.Plugins
					? window.Capacitor.Plugins.Haptics
					: null;

			// If all is available attempt file save
			if (isCapacitorActive && Haptics) {
				await Haptics.vibrate({ duration: 10 });
			}
		} catch (error) {
			alert(error);
		}
	}

	try {
		setTimeout(async () => {
			app.style.opacity = 0;
			// Fetch the HTML file
			const response = await fetch(`pages/${pageName}.html`);
			if (!response.ok) throw new Error("Page not found");

			const html = await response.text();

			if (saveHistory) {
				if (session.currentPage !== pageName) {
					session.pageHistory.push(session.currentPage);
				}
			}
			session.currentPage = pageName;

			loadPageContent(html, pageName, keepScrollPosition);
		}, 0);
	} catch (err) {
		// app.innerHTML = `<p>Error loading page: ${err.message}</p>`;
		alert("Something went wrong with this button.");
		loadPage("home");
	}
}

function reloadPage() {
	loadPage(session.currentPage, false, true);
}

function loadPageContent(html, pageName, keepScrollPosition = false) {
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

		if (!keepScrollPosition) {
			window.scrollTo({
				top: 0,
				behavior: "instant",
			});
		}

		setTimeout(() => {
			app.style.opacity = 1;
		}, 0);
	}, 150);
}

function navigateBack() {
	if (!isFullScreenOpen()) {
		if (session.navigateBackCancelFunction != null) {
			session.navigateBackCancelFunction();
			session.navigateBackCancelFunction = null;
		}

		if (
			session.pageHistory.length > 0 &&
			session.pageHistory[session.pageHistory.length - 1] != ""
		) {
			loadPage(session.pageHistory.pop(), false);
		} else {
			loadPage("home", false);
		}
	} else {
		closeFullScreen();
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
}

loadData();
session.currentCoverIndex = 2;
loadPage("covers");
