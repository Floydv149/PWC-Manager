export function fullScreen(element) {
	const bg = document.createElement("div");
	bg.id = "fullScreenBG";
	// bg.setAttribute("onclick", "closeFullScreen();");
	document.body.appendChild(bg);

	const imageContainer = document.createElement("div");
	imageContainer.classList.add("fullScreenImageContainer");
	bg.appendChild(imageContainer);

	const image = document.createElement("img");
	image.src = element.src;
	image.id = "fullScreenImage";
	image.classList.add("fullScreenImage");
	image.classList.add("rounded");
	imageContainer.appendChild(image);

	const closeButton = document.createElement("img");
	closeButton.src = "/assets/icons/close.png";
	closeButton.classList.add("fullScreenCloseButton");
	closeButton.setAttribute("onclick", "closeFullScreen();");
	bg.appendChild(closeButton);

	// const downloadButton = document.createElement("img");
	// downloadButton.src = "/images/icons/download.png";
	// downloadButton.classList.add("fullScreenDownloadButton");
	// downloadButton.setAttribute(
	// 	"onclick",
	// 	"downloadFile('" +
	// 		element.src +
	// 		"', '" +
	// 		element.src.substring(element.src.lastIndexOf("/") + 1) +
	// 		"');"
	// );
	// bg.appendChild(downloadButton);

	bg.style.opacity = 0;
	// image.style.top = "200vh";
	image.style.transform = "scale(0)";
	closeButton.style.transform = "translateY(-100px)";
	// downloadButton.style.transform = "translateY(-100px)";
	setTimeout(() => {
		bg.style.opacity = 1;
		image.style.top = 0;
		closeButton.style.opacity = 1;
		// downloadButton.style.opacity = 1;
		image.style.transform = "scale(1)";
		closeButton.style.transform = "translateY(0px)";
		// downloadButton.style.transform = "translateY(0px)";
	}, 20);
}

export function closeFullScreen() {
	const bg = document.getElementById("fullScreenBG");
	const image = document.getElementById("fullScreenImage");
	const closeButton = document.querySelector(".fullScreenCloseButton");

	bg.style.opacity = 1;
	image.style.top = 0;
	setTimeout(() => {
		bg.style.opacity = 0;
		image.style.top = "200vh";
		closeButton.style.opacity = 0;
		closeButton.style.transform = "translateY(-100px)";
		// downloadButton.style.opacity = 0;
		// downloadButton.style.transform = "translateY(-100px)";
	}, 20);
	setTimeout(function () {
		bg.remove();
	}, 500);
}

export function isFullScreenOpen() {
	return document.getElementById("fullScreenBG") ? true : false;
}
