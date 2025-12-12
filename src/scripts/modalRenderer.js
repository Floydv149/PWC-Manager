export function openModal(htmlContent) {
	let modals = document.getElementById("modals");

	if (!modals) {
		modals = document.createElement("div");
		modals.id = "modals";
		document.body.appendChild(modals);
	}

	const newModalContainer = document.createElement("div");
	newModalContainer.classList.add("modalContainer");

	const newModalBG = document.createElement("div");
	newModalBG.classList.add("modalBG");

	const newModal = document.createElement("div");
	newModal.classList.add("modal");
	newModal.innerHTML = htmlContent;

	newModalContainer.appendChild(newModalBG);
	newModalContainer.appendChild(newModal);

	modals.appendChild(newModalContainer);

	setTimeout(() => {
		newModalBG.style.background = "rgba(0, 0, 0, 0.7)";
		newModal.style.transform = "scale(1)";
	}, 25);
}

export function closeLastModal() {
	const modal = document.querySelector("#modals:last-child");
	if (modalContainer) {
		modal.firstChild.style.transform = "scale(0)";
		modal.childNodes[1].parentElement.style.background = "rgba(0, 0, 0, 0)";
		setTimeout(function () {
			modal.parentElement.remove();
		}, 1000);
	}
}
