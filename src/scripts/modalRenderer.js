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

export async function openYesNoModal(
	questionHeaderIcon,
	questionHeaderText,
	questionHTML,
	yesAnswer,
	noAnswer
) {
	session.currentModalAnswer = -1;

	return new Promise((resolve) => {
		const modalHTML =
			`
		<h2 class='center'><img src="/assets/icons/` +
			questionHeaderIcon +
			`.png">` +
			questionHeaderText +
			`</h2>
		<div class="modalContent">
			${questionHTML}
			<div class="modalButtons">
				<a class="normal rounded full medium" onclick="session.currentModalAnswer = 1"><img src="/assets/icons/checkBox.png">` +
			yesAnswer +
			`</a>
				<a class="normal rounded full small" onclick="session.currentModalAnswer = 0"><img src="/assets/icons/close.png">` +
			noAnswer +
			`</a>
			</div>
		</div>
		`;
		openModal(modalHTML);

		const checkAnswerInterval = setInterval(() => {
			if (session.currentModalAnswer !== -1) {
				clearInterval(checkAnswerInterval);
				const answer = session.currentModalAnswer;
				session.currentModalAnswer = -1;
				closeLastModal();
				resolve(answer === 1);
			}
		}, 100);
	});
}

export async function openAlertModal(
	alertHeaderIcon,
	alertHeaderText,
	alertHTML,
	buttonText
) {
	session.currentModalAnswer = -1;

	return new Promise((resolve) => {
		const modalHTML =
			`
		<h2 class='center'><img src="/assets/icons/` +
			alertHeaderIcon +
			`.png">` +
			alertHeaderText +
			`</h2>
		<div class="modalContent">
			${alertHTML}
			<div class="modalButtons">
				<a class="normal rounded full medium" onclick="session.currentModalAnswer = 1"><img src="/assets/icons/checkBox.png">` +
			buttonText +
			`</a>
			</div>
		</div>
		`;
		openModal(modalHTML);

		const checkAnswerInterval = setInterval(() => {
			if (session.currentModalAnswer !== -1) {
				clearInterval(checkAnswerInterval);
				const answer = session.currentModalAnswer;
				session.currentModalAnswer = -1;
				closeLastModal();
				resolve(answer === 1);
			}
		}, 100);
	});
}

export function closeLastModal() {
	const modal = document.querySelector("#modals .modalContainer:last-child");
	if (modal) {
		modal.childNodes[0].style.background = "rgba(0, 0, 0, 0)";
		modal.childNodes[1].style.transform = "scale(0)";
		setTimeout(function () {
			modal.remove();
		}, 500);
	}
}
