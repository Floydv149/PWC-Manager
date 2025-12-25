export function generateCartContentHTML(
	cartDesignPublications,
	fullScreenButton = true
) {
	let output = "";

	for (let i = 0; i < cartDesignPublications.length; i++) {
		output += "<div class='cartRow'>";
		for (let j = 0; j < cartDesignPublications[i].length; j++) {
			const publication = data.library[cartDesignPublications[i][j]];
			if (fullScreenButton) {
				output +=
					'<img onclick="fullScreen(this)" src="' +
					publication.imageURL +
					'" alt="' +
					publication.title +
					'">';
			} else {
				output +=
					'<img src="' +
					publication.imageURL +
					'" alt="' +
					publication.title +
					'">';
			}
		}
		output += "</div>";
	}

	return output;
}
